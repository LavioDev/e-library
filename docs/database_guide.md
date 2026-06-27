# Hướng dẫn Quản lý, Backup và Kiểm tra Database trên VPS

Tài liệu này ghi nhận trạng thái cơ sở dữ liệu (Database), cấu hình kết nối, hướng dẫn sao lưu (Backup), khôi phục (Restore), và cách truy cập kiểm tra dữ liệu trực tiếp trên VPS.

---

## 1. Trạng thái Database & Cấu hình Kết nối (Cập nhật ngày 07/06/2026)

Hệ thống cơ sở dữ liệu chạy dưới dạng container Docker (`e_library_mysql`) sử dụng MySQL 8.0.

### Thông số cấu hình (từ `.env` / `docker-compose.yml`):
*   **Driver**: MySQL
*   **Database Name**: `e_library`
*   **Root User**: `root` / **Root Password**: `root`
*   **App User**: `e_library_user` / **App Password**: `e_library_pass`
*   **Cổng kết nối (Internal)**: `3306` (dùng trong Docker Network)
*   **Cổng kết nối (External/Host VPS)**: `3307` (được map ra ngoài để truy cập từ ngoài host)

### Các bảng dữ liệu chính:
*   `users`: Lưu thông tin tài khoản (Giáo viên, Học sinh).
*   `texts`: Thông tin chung của văn bản (tên, tác giả, link đọc rộng `read_link`).
*   `text_documents`: Lưu tài liệu văn bản chi tiết (.docx).
*   `text_files`: Lưu thông tin tệp đa phương tiện đính kèm (ảnh, video, audio).
*   `text_links`: **(Bảng mới tạo hôm nay 07/06/2026)** Lưu liên kết YouTube và Google Drive phục vụ hiển thị nhúng trong văn bản.
*   `comments`: Lưu bình luận bài học của học sinh.

---

## 2. Hướng dẫn Sao lưu (Backup) Database

Bạn có hai cách để backup dữ liệu trên VPS (đứng tại thư mục dự án `/var/www/e-library`):

### Cách 1: Sử dụng Script tự động (Khuyên dùng)
Dự án đã cấu hình sẵn script Bash tự động lưu, nén và dọn dẹp các bản backup cũ:
```bash
chmod +x ./scripts/backup-db.sh
./scripts/backup-db.sh
```
*   **Cơ chế hoạt động**:
    1.  Tạo thư mục `/var/www/e-library/backups` nếu chưa có.
    2.  Xuất cơ sở dữ liệu ra tệp `.sql` dạng `backup_e_library_YYYYMMDD_HHMMSS.sql`.
    3.  Tự động nén thành `.sql.gz` để tiết kiệm không gian đĩa trên VPS.
    4.  Tự động xóa các bản sao lưu đã cũ hơn 30 ngày.

### Cách 2: Thực hiện thủ công bằng một dòng lệnh
Nếu bạn muốn xuất nhanh ra một file chỉ định ngay lập tức:
```bash
docker exec -i e_library_mysql mysqldump -uroot -proot e_library > backup.sql
```

---

## 3. Hướng dẫn Khôi phục (Restore) Database

Trong trường hợp cần đưa dữ liệu từ bản backup vào lại hệ thống:

```bash
docker exec -i e_library_mysql mysql -uroot -proot e_library < path/to/backup.sql
```
*(Nếu khôi phục từ file nén `.gz`, hãy giải nén bằng lệnh `gunzip filename.sql.gz` trước).*

---

## 4. Hướng dẫn Kiểm tra và Truy vấn trực tiếp trên VPS

Để kiểm tra xem dữ liệu đã được lưu đúng hay chưa, bạn có thể truy cập thẳng vào MySQL CLI của container:

### Bước 1: Vào MySQL Console của Docker container
```bash
docker exec -it e_library_mysql mysql -uroot -proot e_library
```

### Bước 2: Một số lệnh SQL cơ bản để kiểm tra dữ liệu

*   **Hiển thị toàn bộ các bảng hiện có**:
    ```sql
    SHOW TABLES;
    ```

*   **Kiểm tra danh sách các liên kết YouTube/Google Drive đã lưu**:
    ```sql
    SELECT * FROM text_links;
    ```

*   **Kiểm tra danh sách tệp đính kèm**:
    ```sql
    SELECT id, file_name, file_type, file_size FROM text_files;
    ```

*   **Kiểm tra số lượng văn bản hiện có**:
    ```sql
    SELECT COUNT(*) FROM texts;
    ```

### Bước 3: Thoát khỏi MySQL Console
```sql
exit;
```

---

## 5. Hướng dẫn Kiểm tra tính hợp lệ của file Backup .sql đã tạo

Sau khi chạy lệnh backup, để kiểm tra xem file `.sql` đã tạo ra có đúng định dạng và có dữ liệu hay không, bạn chạy các lệnh sau trên VPS:

### 1. Kiểm tra sự tồn tại và dung lượng file
```bash
ls -lh backup.sql
```
*Đảm bảo dung lượng file lớn hơn `0` (không bị trống).*

### 2. Xem 20 dòng đầu tiên của file SQL
```bash
head -n 20 backup.sql
```
*Một file backup hợp lệ sẽ có dòng tiêu đề mở đầu dạng: `-- MySQL dump 10.13  Distrib...`.*

### 3. Tìm các bảng đã được lưu trong file backup
```bash
grep -i "CREATE TABLE" backup.sql
```
*Lệnh này sẽ liệt kê tất cả các bảng đã được đóng gói trong file SQL để đảm bảo không bị thiếu bảng nào.*

### 4. Đếm tổng số dòng lệnh SQL trong file
```bash
wc -l backup.sql
```
