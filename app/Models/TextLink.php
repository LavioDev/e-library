<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TextLink extends Model
{
    protected $fillable = [
        'text_id',
        'url',
    ];

    /**
     * @return BelongsTo<Text, $this>
     */
    public function text(): BelongsTo
    {
        return $this->belongsTo(Text::class);
    }

    /**
     * Get the file type of a Google Drive link (video, image, or other).
     *
     * @return string
     */
    public function getDriveType(): string
    {
        if (!preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $this->url, $driveMatch)) {
            return 'other';
        }

        $fileId = $driveMatch[1];

        return \Illuminate\Support\Facades\Cache::remember('drive_file_type_' . $fileId, now()->addDays(30), function() use ($fileId) {
            // 1. Try a cURL HEAD request to the direct download link
            $url = "https://drive.google.com/uc?export=download&id=" . $fileId;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
            curl_exec($ch);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            curl_close($ch);

            if ($contentType) {
                if (str_starts_with($contentType, 'video/')) {
                    return 'video';
                }
                if (str_starts_with($contentType, 'image/')) {
                    return 'image';
                }
            }

            // 2. Fallback: GET the view page and search for the internal Google viewer configuration
            $viewUrl = "https://drive.google.com/file/d/" . $fileId . "/view";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $viewUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
            $html = curl_exec($ch);
            curl_close($ch);

            if ($html && preg_match('/"docs-dm"\s*:\s*"([^"]+)"/', $html, $m)) {
                $mime = $m[1];
                if (str_starts_with($mime, 'video/')) {
                    return 'video';
                }
                if (str_starts_with($mime, 'image/')) {
                    return 'image';
                }
            }

            // Default to image if we couldn't determine
            return 'image';
        });
    }
}
