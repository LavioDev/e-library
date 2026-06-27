# E-Library

E-Library là hệ thống quản lý thư viện bài đọc cho lớp học, gồm 2 vai trò chính:
- `teacher`: quản lý nội dung đọc, lớp học, bài tập và chấm điểm.
- `user` (student): đọc văn bản, làm bài, nộp bài và theo dõi kết quả.

Ứng dụng sử dụng Laravel + Blade cho web và có API dùng Sanctum.

## Công nghệ chính
- PHP `^8.3`
- Laravel `^13.8`
- Laravel Sanctum `^4.0`
- Vite + TailwindCSS 4 + DaisyUI
- CKEditor 5
- PHPWord (`phpoffice/phpword`) cho import/export DOCX

## Tính năng nổi bật
- Quản lý văn bản và loại văn bản
- Soạn thảo, xem trước, import/export DOCX
- Quản lý lớp đọc và gán học viên
- Tạo bài tập, câu hỏi và phát hành theo lớp
- Học viên làm bài, lưu nháp, nộp bài
- Giáo viên chấm điểm, phản hồi và theo dõi kết quả
- Bình luận theo văn bản
- Tìm kiếm nhanh theo role từ modal search trên header
    
## Yêu cầu môi trường
- PHP 8.3+
- Composer
- Node.js + npm
- MySQL/MariaDB (hoặc cấu hình DB tương thích trong `.env`)

## Cài đặt & chạy local
### Cách nhanh
```bash
composer setup
composer dev
```

### Cách thủ công
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
php artisan serve
```

## Tài khoản & phân quyền
- `teacher`: truy cập khu vực quản trị (`/admin/...`), quản lý dữ liệu và chấm bài.
- `user`: truy cập khu vực học viên (`/texts/...`, `/my-assignments/...`).

Bạn có thể seed dữ liệu mẫu (nếu dự án đã cấu hình seeder):
```bash
php artisan db:seed
```

## Lệnh hữu ích
```bash
# chạy test
php artisan test

# format/lint (nếu dùng Pint)
./vendor/bin/pint

# clear cache
php artisan optimize:clear
```

## Cấu trúc thư mục (rút gọn)
- `app/Http/Controllers/Web`: controller cho giao diện web
- `app/Http/Controllers/Api`: controller cho API
- `resources/views`: Blade templates
- `routes/web.php`: route web
- `routes/api.php`: route API

## Ghi chú
- Nếu upload/import DOCX, kiểm tra quyền ghi thư mục `storage`.
- Nếu thay đổi giao diện, chạy lại `npm run dev` hoặc `npm run build`.
