# AGENT.md

## Mục tiêu nền tảng
- Dự án là web app thư viện online dùng Laravel 13 theo hướng Blade-first.
- Giao diện hiển thị bằng tiếng Việt.
- Backend giữ API naming, log và internal message bằng tiếng Anh.

## Quy ước frontend
- Stack giao diện mặc định là Tailwind CSS v4 + daisyUI.
- Theme chủ đạo dùng tông `blue` theo hướng sáng, sạch và gọn.
- Cỡ chữ nền mặc định là `text-sm`.
- Bo góc mặc định ưu tiên `rounded-sm`.
- Font giao diện mặc định là `Bahnschrift`, có fallback an toàn.
- Không tự tạo helper hoặc abstraction có tên `normalize*` hay `resolve*` nếu framework không bắt buộc.

## Quy ước cho Blade UI
- Với các component cứng, tĩnh và chỉ phục vụ một trang cụ thể:
  - không cần tách thành service dữ liệu mẫu
  - không cần render qua vòng lặp hoặc mảng cấu hình nếu nội dung đã cố định
  - ưu tiên ghi thẳng markup vào Blade của trang đó
- Chỉ tách partial hoặc component khi phần UI đó thực sự được tái sử dụng ở nhiều nơi.
- Không đưa dữ liệu mẫu hardcode vào service chỉ để render homepage tĩnh.
- Với các trang public và auth:
  - không hiển thị mô tả hệ thống, mô tả API, token, session hoặc chi tiết kỹ thuật trên UI
  - copy phải ngắn, trực diện và phục vụ hành động chính của người dùng
  - trạng thái thành công hoặc thất bại chỉ hiển thị ngắn gọn khi cần

## Quy ước backend
- Mọi luồng fetch mặc định đi theo thứ tự:
  `fetch + token -> route -> FormRequest -> controller -> service/querybuilder -> response`
- Tất cả fetch endpoint mặc định phải được bảo vệ bằng `auth:sanctum`, trừ endpoint public được mô tả rõ trong tài liệu.
- Tên route, URI API, controller action, service method và key nghiệp vụ trong API phải dùng tiếng Anh.
- Controller phải thin:
  - chỉ nhận dữ liệu đã validate
  - chỉ gọi service tương ứng
  - chỉ trả response
  - không viết query phức tạp hoặc orchestration nghiệp vụ trong controller

## Service và query builder
- Tách logic vào `app/Services`.
- Mỗi model hoặc context có thư mục riêng, ví dụ:
  - `app/Services/Auth/AuthService.php`
  - `app/Services/User/UserService.php`
  - `app/Services/User/UserQueryBuilder.php`
- `*QueryBuilder.php` chứa logic truy vấn và tái sử dụng query.
- `*Service.php` chứa orchestration và business logic.

## Request, response và model
- Mọi input API phải đi qua `FormRequest`.
- Giữ response JSON đơn giản theo chuẩn Laravel, không tự ép một global envelope nếu chưa có yêu cầu riêng.
- Khi tạo model mới, luôn dùng:
  `php artisan make:model Name --all`
- Ngay sau khi tạo model mới:
  - khai báo `$fillable`
  - khai báo quan hệ với các model liên quan

## Chuẩn hiện tại của base
- Auth reference slice mặc định gồm:
  - `POST /api/auth/login`
  - `POST /api/auth/logout`
  - `GET /api/auth/me`
- `login` là public endpoint.
- `logout` và `me` yêu cầu `auth:sanctum`.

## Chuẩn Giao Diện Quản Trị (Admin UI Reference)
- Giao diện quản lý/quản trị chuẩn được tham chiếu tại trang quản lý văn bản:
  - File Blade: [index.blade.php](file:///c:/Users/khanh/Projects/e-library/resources/views/texts/index.blade.php) (Tương ứng URL `/admin/texts`)
- Các quy chuẩn thiết kế từ trang mẫu này để áp dụng cho các trang quản trị khác khi refactor:
  - **Màu sắc & Phông chữ**: Kế thừa tông be-trắng cao cấp, phông chữ `Bahnschrift` cho UI hệ thống và `EB Garamond` cho tiêu đề bảng, nhãn nhóm.
  - **Nhãn mức độ (Difficulty Badge)**: Hiển thị dạng chữ hoa thường tự nhiên (Sentence Case) như `Dễ`, `Trung bình`, `Khó` (bỏ `uppercase`), sử dụng class `whitespace-nowrap inline-block` để ngăn ngắt dòng.
  - **Bộ phân trang (Pagination)**: Dùng bộ phân trang thông minh dynamic ellipsis (`< 1 2 … 5 … 10 >`) thông qua `data-pagination` và nạp module [pagination.js](file:///c:/Users/khanh/Projects/e-library/resources/js/library/pagination.js) thay vì render Laravel Blade `links()` mặc định.

