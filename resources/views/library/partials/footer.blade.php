<footer class="lib-footer">
    <div class="lib-footer-container">
        {{-- Tier 1: Multi-column directory --}}
        <div class="lib-footer-grid">
            
            {{-- Column 1: Brand & Slogan --}}
            <div class="lib-footer-col lib-footer-brand-col">
                <div class="lib-footer-brand">
                    <span class="lib-footer-logo" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.75 6.75A2.75 2.75 0 0 1 7.5 4h9A2.75 2.75 0 0 1 19.25 6.75v10.5A2.75 2.75 0 0 1 16.5 20h-9a2.75 2.75 0 0 1-2.75-2.75V6.75Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 8.5h7.5M8.25 12h7.5M8.25 15.5h4.5" />
                        </svg>
                    </span>
                    <div>
                        <p class="lib-footer-brand-name">E-Library</p>
                        <p class="lib-footer-brand-sub font-serif italic">Kho tàng tri thức văn học</p>
                    </div>
                </div>
                <p class="lib-footer-desc">
                    Nền tảng chia sẻ và lưu trữ các tác phẩm văn học chọn lọc phục vụ mục đích học tập và giảng dạy tại Việt Nam.
                </p>
            </div>

            {{-- Column 2: Navigation --}}
            <div class="lib-footer-col">
                <h4 class="lib-footer-title">Khám phá</h4>
                <ul class="lib-footer-links">
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a href="#lib-texts">Bộ sưu tập</a></li>
                    <li><a href="#">Tác phẩm nổi bật</a></li>
                    <li><a href="#">Tài liệu mới cập nhật</a></li>
                </ul>
            </div>

            {{-- Column 3: Categories --}}
            <div class="lib-footer-col">
                <h4 class="lib-footer-title">Thể loại chính</h4>
                <ul class="lib-footer-links">
                    <li><a href="#">Thơ ca Việt Nam</a></li>
                    <li><a href="#">Văn xuôi & Tiểu thuyết</a></li>
                    <li><a href="#">Truyện ngắn chọn lọc</a></li>
                    <li><a href="#">Kịch bản & Nghị luận</a></li>
                </ul>
            </div>

            {{-- Column 4: Contact & Legal --}}
            <div class="lib-footer-col">
                <h4 class="lib-footer-title">Hỗ trợ</h4>
                <ul class="lib-footer-links">
                    <li><a href="#">Liên hệ góp ý</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Bản quyền & Khiếu nại</a></li>
                </ul>
            </div>

        </div>

        {{-- Tier 2: Bottom copyright bar --}}
        <div class="lib-footer-bottom">
            <p class="lib-footer-copy">
                © {{ date('Y') }} E-Library — Hệ thống thư viện điện tử mở.
            </p>
            <p class="lib-footer-credit">
                Được thiết kế bởi
                <button type="button" id="laviodev-btn" class="laviodev-link" onclick="document.getElementById('laviodev-modal').classList.add('is-open')" aria-haspopup="dialog">
                    LavioDev
                </button>
            </p>
        </div>

        {{-- LavioDev Modal --}}
        <div id="laviodev-modal" class="laviodev-modal" role="dialog" aria-modal="true" aria-labelledby="laviodev-modal-title" onclick="if(event.target===this)this.classList.remove('is-open')">
            <div class="laviodev-modal-card">
                <button class="laviodev-modal-close" onclick="document.getElementById('laviodev-modal').classList.remove('is-open')" aria-label="Đóng">&times;</button>
                <h3 id="laviodev-modal-title" class="laviodev-modal-name">LavioDev</h3>
                <p class="laviodev-modal-role">Nhà phát triển &amp; Thiết kế hệ thống</p>
                <div class="laviodev-modal-divider"></div>
                <ul class="laviodev-modal-contacts">
                    <li>
                        <span class="laviodev-contact-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </span>
                        <a href="mailto:khanhnd05@gmail.com" class="laviodev-contact-link">khanhnd05@gmail.com</a>
                    </li>
                    <li>
                        <span class="laviodev-contact-icon laviodev-fb-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </span>
                        <a href="https://www.facebook.com/nguyen.khanh.201930" target="_blank" rel="noopener noreferrer" class="laviodev-contact-link">facebook.com/nguyen.khanh.201930</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<style>
.lib-footer {
    border-top: 1px solid oklch(90% 0.014 72);
    background: linear-gradient(180deg,
        oklch(99.4% 0.003 78) 0%,
        oklch(97.8% 0.008 74) 100%);
    padding-top: 3.5rem;
    padding-bottom: 2rem;
}

.lib-footer-container {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Tier 1 Grid */
.lib-footer-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2.5rem;
    padding-bottom: 3rem;
    border-bottom: 1px solid oklch(92% 0.010 72);
}

@media (min-width: 640px) {
    .lib-footer-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .lib-footer-grid {
        grid-template-columns: 2fr 1fr 1fr 1fr;
    }
}

/* Brand Column styling */
.lib-footer-brand-col {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
.lib-footer-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.lib-footer-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.6rem;
    background: oklch(40% 0.068 54);
    color: oklch(98% 0.005 78);
    flex-shrink: 0;
    box-shadow: 0 4px 12px -3px oklch(40% 0.068 54 / 0.45);
}
.lib-footer-brand-name {
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: oklch(25% 0.022 56);
}
.lib-footer-brand-sub {
    font-size: 0.72rem;
    color: oklch(50% 0.030 60);
    margin-top: 0.1rem;
}
.lib-footer-desc {
    font-size: 0.80rem;
    line-height: 1.6;
    color: oklch(46% 0.015 62);
    max-width: 22rem;
}

/* Column Headers & List links */
.lib-footer-title {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: oklch(35% 0.025 58);
    margin-bottom: 1.25rem;
}
.lib-footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.lib-footer-links a {
    font-size: 0.80rem;
    color: oklch(46% 0.015 62);
    text-decoration: none;
    transition: color 0.18s ease, transform 0.18s ease;
    display: inline-block;
}
.lib-footer-links a:hover {
    color: oklch(36% 0.050 54);
    transform: translateX(2px);
}

/* Tier 2: Bottom copyright bar */
.lib-footer-bottom {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding-top: 2rem;
    align-items: center;
}

@media (min-width: 768px) {
    .lib-footer-bottom {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }
}

.lib-footer-copy {
    font-size: 0.76rem;
    color: oklch(58% 0.012 64);
    text-align: center;
}

@media (min-width: 768px) {
    .lib-footer-copy {
        text-align: left;
    }
}

.lib-footer-credit {
    font-size: 0.76rem;
    color: oklch(58% 0.012 64);
    text-align: center;
    white-space: nowrap;
}

@media (min-width: 768px) {
    .lib-footer-credit {
        text-align: right;
        margin-left: auto;
    }
}

/* LavioDev underline button */
.laviodev-link {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    font-size: inherit;
    font-family: inherit;
    color: oklch(40% 0.068 54);
    text-decoration: underline;
    text-underline-offset: 2px;
    transition: color 0.18s ease, opacity 0.18s ease;
}
.laviodev-link:hover {
    color: oklch(28% 0.065 54);
    opacity: 0.85;
}

/* LavioDev Modal overlay */
.laviodev-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    animation: lavioFadeIn 0.2s ease;
}
.laviodev-modal.is-open {
    display: flex;
}
@keyframes lavioFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}

/* Modal card */
.laviodev-modal-card {
    position: relative;
    background: #fff;
    border-radius: 1.25rem;
    padding: 2.5rem 2rem 2rem;
    max-width: 22rem;
    width: 90%;
    box-shadow: 0 24px 60px -8px rgba(0,0,0,0.22), 0 4px 16px -4px rgba(0,0,0,0.12);
    animation: lavioSlideUp 0.25s cubic-bezier(.22,.68,0,1.2);
    text-align: center;
}
@keyframes lavioSlideUp {
    from { transform: translateY(24px); opacity: 0; }
    to   { transform: translateY(0);   opacity: 1; }
}

/* Close button */
.laviodev-modal-close {
    position: absolute;
    top: 0.85rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
    color: oklch(58% 0.012 64);
    transition: color 0.15s;
}
.laviodev-modal-close:hover { color: oklch(25% 0.022 56); }

.laviodev-modal-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: oklch(22% 0.022 56);
    margin: 0 0 0.25rem;
}
.laviodev-modal-role {
    font-size: 0.78rem;
    color: oklch(52% 0.018 62);
    margin: 0;
}
.laviodev-modal-divider {
    height: 1px;
    background: oklch(92% 0.010 72);
    margin: 1.25rem 0;
}

/* Contact list */
.laviodev-modal-contacts {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
    text-align: left;
}
.laviodev-modal-contacts li {
    display: flex;
    align-items: center;
    gap: 0.65rem;
}
.laviodev-contact-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border-radius: 0.5rem;
    background: oklch(97% 0.006 72);
    color: oklch(42% 0.068 54);
    flex-shrink: 0;
}
.laviodev-contact-icon svg { width: 1rem; height: 1rem; }
.laviodev-fb-icon { color: #1877f2; background: #e7f0fd; }
.laviodev-contact-link {
    font-size: 0.80rem;
    color: oklch(35% 0.040 56);
    text-decoration: none;
    word-break: break-all;
    transition: color 0.15s;
}
.laviodev-contact-link:hover { color: oklch(42% 0.068 54); text-decoration: underline; }

.lib-footer-bottom-links {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.lib-footer-ver {
    font-size: 0.72rem;
    color: oklch(70% 0.008 64);
    border: 1px solid oklch(90% 0.010 70);
    padding: 0.18rem 0.50rem;
    border-radius: var(--r-sm);
    background: oklch(99.0% 0.002 78);
}
</style>
