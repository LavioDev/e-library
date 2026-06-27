<?php

namespace App\Services;

use App\Models\TextDocument;
use DOMDocument;
use DOMElement;
use DOMNode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use Throwable;

class TextDocumentPreviewService
{
    /**
     * @return array{html: string, error: null|string}
     */
    public function buildPreview(TextDocument $document, int $textId, string $imageRouteName = 'admin.texts.writer.preview-image'): array
    {
        try {
            if (!$document->file_path || !Storage::disk('local')->exists($document->file_path)) {
                return [
                    'html' => '<p>Chưa có tệp DOCX để hiển thị preview.</p>',
                    'error' => null,
                ];
            }

            $phpWord = IOFactory::load(Storage::disk('local')->path($document->file_path), 'Word2007');
            $writer = IOFactory::createWriter($phpWord, 'HTML');
            $writer->setDefaultWhiteSpace('pre-wrap');
            $writer->setDefaultGenericFont('serif');

            $tmpFile = tempnam(sys_get_temp_dir(), 'preview_html_');
            if ($tmpFile === false) {
                throw new \RuntimeException('Cannot create temp file for preview.');
            }

            $writer->save($tmpFile);
            $rawHtml = (string) file_get_contents($tmpFile);
            @unlink($tmpFile);

            $html = $this->extractBodyContent($rawHtml);
            $html = $this->persistEmbeddedImages($html, $textId, $imageRouteName);
            $html = $this->sanitizeHtml($html);
            $html = trim($html);

            if ($html === '') {
                $html = '<p>Không có nội dung để hiển thị preview.</p>';
            }

            return ['html' => $html, 'error' => null];
        } catch (Throwable $e) {
            Log::warning('DOCX preview generation failed.', [
                'text_document_id' => $document->id,
                'text_id' => $textId,
                'message' => $e->getMessage(),
            ]);

            return [
                'html' => '<p>Không thể tạo preview cho tệp DOCX hiện tại.</p>',
                'error' => 'Không thể tạo preview DOCX. Bạn vẫn có thể nhập/xuất tệp và thử lại sau.',
            ];
        }
    }

    private function extractBodyContent(string $rawHtml): string
    {
        if ($rawHtml === '') {
            return '';
        }

        $dom = $this->loadDomDocument($rawHtml);
        $body = $dom->getElementsByTagName('body')->item(0);
        if (!$body instanceof DOMElement) {
            return $rawHtml;
        }

        return $this->innerHtml($body, $dom);
    }

    private function persistEmbeddedImages(string $html, int $textId, string $imageRouteName): string
    {
        $dom = $this->loadDomDocument('<div id="preview-root">' . $html . '</div>');
        $root = $dom->getElementsByTagName('div')->item(0);
        if (!$root instanceof DOMElement) {
            return $html;
        }

        $imageNodes = $root->getElementsByTagName('img');
        if ($imageNodes->length === 0) {
            return $html;
        }

        $baseDir = "text-previews/{$textId}";
        Storage::disk('public')->deleteDirectory($baseDir);
        Storage::disk('public')->makeDirectory($baseDir);

        $index = 0;
        /** @var DOMElement $image */
        foreach ($imageNodes as $image) {
            $src = (string) $image->getAttribute('src');
            if (!str_starts_with($src, 'data:')) {
                continue;
            }

            if (!preg_match('/^data:(?<mime>[\w\/\-\+\.]+);base64,(?<data>.+)$/', $src, $matches)) {
                continue;
            }

            $binary = base64_decode($matches['data'], true);
            if ($binary === false) {
                continue;
            }

            $extension = $this->extensionFromMime($matches['mime']);
            $relativePath = $baseDir . '/img-' . $index . '.' . $extension;
            if (!Storage::disk('public')->put($relativePath, $binary)) {
                continue;
            }

            $image->setAttribute('src', route($imageRouteName, [
                'text' => $textId,
                'filename' => basename($relativePath),
            ]));
            $index++;
        }

        return $this->innerHtml($root, $dom);
    }

    private function extensionFromMime(string $mime): string
    {
        return match (strtolower($mime)) {
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
            default => 'jpg',
        };
    }

    private function sanitizeHtml(string $html): string
    {
        $allowedTags = [
            'div', 'p', 'span', 'br', 'strong', 'b', 'em', 'i', 'u', 's',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'ul', 'ol', 'li',
            'table', 'thead', 'tbody', 'tr', 'th', 'td',
            'blockquote', 'a', 'img',
        ];

        $allowedAttrs = [
            'class', 'style', 'colspan', 'rowspan',
            'href', 'target', 'rel',
            'src', 'alt', 'width', 'height',
        ];

        $dom = $this->loadDomDocument('<div id="preview-root">' . $html . '</div>');
        $root = $dom->getElementsByTagName('div')->item(0);
        if (!$root instanceof DOMElement) {
            return '';
        }

        $this->sanitizeNode($root, $allowedTags, $allowedAttrs);

        return $this->innerHtml($root, $dom);
    }

    /**
     * @param array<int, string> $allowedTags
     * @param array<int, string> $allowedAttrs
     */
    private function sanitizeNode(DOMNode $node, array $allowedTags, array $allowedAttrs): void
    {
        for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
            $child = $node->childNodes->item($i);
            if (!$child instanceof DOMNode) {
                continue;
            }

            if ($child->nodeType === XML_COMMENT_NODE) {
                $node->removeChild($child);
                continue;
            }

            if ($child->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            /** @var DOMElement $child */
            $tag = strtolower($child->tagName);

            if (!in_array($tag, $allowedTags, true)) {
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            if ($child->hasAttributes()) {
                for ($attrIndex = $child->attributes->length - 1; $attrIndex >= 0; $attrIndex--) {
                    $attribute = $child->attributes->item($attrIndex);
                    if ($attribute === null) {
                        continue;
                    }

                    $attrName = strtolower($attribute->name);
                    $attrValue = $attribute->value;

                    if (!in_array($attrName, $allowedAttrs, true)) {
                        $child->removeAttribute($attribute->name);
                        continue;
                    }

                    if ($attrName === 'style') {
                        $cleanStyle = $this->sanitizeStyle($attrValue);
                        if ($cleanStyle === '') {
                            $child->removeAttribute('style');
                        } else {
                            $child->setAttribute('style', $cleanStyle);
                        }
                    }

                    if ($attrName === 'href' && !preg_match('/^(https?:|mailto:|\/|#)/i', $attrValue)) {
                        $child->removeAttribute('href');
                    }

                    if ($attrName === 'src' && !preg_match('/^(https?:|\/)/i', $attrValue)) {
                        $child->removeAttribute('src');
                    }
                }
            }

            $this->sanitizeNode($child, $allowedTags, $allowedAttrs);
        }
    }

    private function sanitizeStyle(string $style): string
    {
        $allowedProperties = [
            'text-align',
            'font-weight',
            'font-style',
            'text-decoration',
            'font-size',
            'font-family',
            'line-height',
            'white-space',
            'vertical-align',
            'margin',
            'margin-left',
            'margin-right',
            'margin-top',
            'margin-bottom',
            'padding',
            'padding-left',
            'padding-right',
            'padding-top',
            'padding-bottom',
            'color',
            'background-color',
            'width',
            'height',
            'max-width',
            'border',
            'border-left',
            'border-right',
            'border-top',
            'border-bottom',
            'list-style-type',
        ];

        $safe = [];
        foreach (explode(';', $style) as $declaration) {
            if (!str_contains($declaration, ':')) {
                continue;
            }

            [$property, $value] = array_map('trim', explode(':', $declaration, 2));
            $property = strtolower($property);
            $value = trim($value);
            if ($property === '' || $value === '') {
                continue;
            }

            if (!in_array($property, $allowedProperties, true)) {
                continue;
            }

            if (preg_match('/expression|javascript:|url\(/i', $value)) {
                continue;
            }

            $safe[] = $property . ': ' . $value;
        }

        return implode('; ', $safe);
    }

    private function innerHtml(DOMElement $root, DOMDocument $dom): string
    {
        $html = '';
        foreach ($root->childNodes as $child) {
            $html .= $dom->saveHTML($child) ?: '';
        }

        return $html;
    }

    private function loadDomDocument(string $html): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');

        $previousUseInternalErrors = libxml_use_internal_errors(true);
        $normalizedHtml = $this->normalizeToUtf8($html);
        $encodedHtml = mb_encode_numericentity(
            $normalizedHtml,
            [0x80, 0x10FFFF, 0, 0x10FFFF],
            'UTF-8'
        );

        $dom->loadHTML($encodedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        return $dom;
    }

    private function normalizeToUtf8(string $value): string
    {
        if (mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        $detectedEncoding = mb_detect_encoding(
            $value,
            ['UTF-8', 'Windows-1252', 'ISO-8859-1', 'ASCII'],
            true
        );

        if ($detectedEncoding === false) {
            $detectedEncoding = 'Windows-1252';
        }

        return mb_convert_encoding($value, 'UTF-8', $detectedEncoding);
    }
}
