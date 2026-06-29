/**
 * library/pagination.js
 * ─────────────────────────────────────────────────────────────────
 * Smart pagination renderer — renders < 1 2 … 5 … 9 10 > style,
 * replacing a static server-rendered block.
 *
 * Usage (auto-init):
 *   The script looks for  data-pagination  containers on DOMContentLoaded.
 *   Required data attributes on the container:
 *     data-pagination
 *     data-current-page="3"
 *     data-last-page="12"
 *     data-base-url="/library"   ← URL without ?page= param
 *     data-param="page"           ← optional, default "page"
 *     data-window="2"             ← optional, pages around current, default 2
 *
 * The script REPLACES the container's innerHTML with the rendered markup.
 * CSS classes used: lib-pagi-wrap, lib-pagi-btn, lib-pagi-active,
 *                   lib-pagi-disabled, lib-pagi-ellipsis
 * ─────────────────────────────────────────────────────────────────
 */

(function (global) {
    'use strict';

    /* ── SVG arrows ──────────────────────────────────────────────── */
    const SVG_PREV = `<svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>`;
    const SVG_NEXT = `<svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>`;

    /* ── Helpers ─────────────────────────────────────────────────── */
    function buildUrl(baseUrl, param, page) {
        try {
            const url = new URL(baseUrl, window.location.origin);
            if (page > 1) {
                url.searchParams.set(param, String(page));
            } else {
                url.searchParams.delete(param);
            }
            return url.toString();
        } catch (_) {
            return baseUrl + (page > 1 ? `?${param}=${page}` : '');
        }
    }

    /**
     * Compute the list of page tokens to render.
     * Returns an array of numbers or the string '…'.
     *
     * Example: current=5, last=12, window=2
     *   → [1, '…', 3, 4, 5, 6, 7, '…', 12]
     */
    function computePages(current, last, win) {
        if (last <= 1) return [1];

        const set = new Set();

        // Always include first & last
        set.add(1);
        set.add(last);

        // Window around current
        for (let i = current - win; i <= current + win; i++) {
            if (i >= 1 && i <= last) set.add(i);
        }

        const sorted = [...set].sort((a, b) => a - b);

        // Insert ellipses where there are gaps > 1
        const result = [];
        for (let i = 0; i < sorted.length; i++) {
            result.push(sorted[i]);
            if (i + 1 < sorted.length && sorted[i + 1] - sorted[i] > 1) {
                result.push('…');
            }
        }
        return result;
    }

    /* ── Renderer ────────────────────────────────────────────────── */
    function renderPagination(container) {
        const current  = parseInt(container.dataset.currentPage, 10);
        const last     = parseInt(container.dataset.lastPage, 10);
        const baseUrl  = container.dataset.baseUrl  || window.location.pathname;
        const param    = container.dataset.param     || 'page';
        const win      = parseInt(container.dataset.window || '2', 10);

        if (!current || !last || last <= 1) {
            container.hidden = true;
            return;
        }

        const pages = computePages(current, last, win);

        const parts = [];

        /* ── Prev button ── */
        if (current <= 1) {
            parts.push(`<span class="lib-pagi-disabled" aria-disabled="true">${SVG_PREV}</span>`);
        } else {
            parts.push(`<a href="${buildUrl(baseUrl, param, current - 1)}" class="lib-pagi-btn" title="Trang trước" aria-label="Trang trước">${SVG_PREV}</a>`);
        }

        /* ── Page tokens ── */
        for (const token of pages) {
            if (token === '…') {
                parts.push(`<span class="lib-pagi-ellipsis" aria-hidden="true">…</span>`);
            } else if (token === current) {
                parts.push(`<span class="lib-pagi-active" aria-current="page">${token}</span>`);
            } else {
                parts.push(`<a href="${buildUrl(baseUrl, param, token)}" class="lib-pagi-btn" aria-label="Trang ${token}">${token}</a>`);
            }
        }

        /* ── Next button ── */
        if (current >= last) {
            parts.push(`<span class="lib-pagi-disabled" aria-disabled="true">${SVG_NEXT}</span>`);
        } else {
            parts.push(`<a href="${buildUrl(baseUrl, param, current + 1)}" class="lib-pagi-btn" title="Trang sau" aria-label="Trang sau">${SVG_NEXT}</a>`);
        }

        container.innerHTML = parts.join('');
    }

    /* ── Auto-init ───────────────────────────────────────────────── */
    function init() {
        document.querySelectorAll('[data-pagination]').forEach(renderPagination);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    /* ── Public API (optional manual call) ──────────────────────── */
    global.LibPagination = { render: renderPagination, init };

})(window);
