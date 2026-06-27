#!/bin/bash

# Thư mục lưu file backup (có thể tùy chỉnh)
BACKUP_DIR="./backups"
DB_NAME="e_library"
CONTAINER_NAME="e_library_mysql"
DB_USER="root"
DB_PASS="root" # Mật khẩu root cấu hình trong docker-compose.yml

# Tạo thư mục backup nếu chưa có
mkdir -p "$BACKUP_DIR"

# Tên file backup kèm timestamp
FILE_NAME="backup_${DB_NAME}_$(date +'%Y%m%d_%H%M%S').sql"
FILE_PATH="${BACKUP_DIR}/${FILE_NAME}"

echo "[$((date))]: Đang khởi động backup database '$DB_NAME'..."

# Thực hiện lệnh mysqldump từ trong docker container ra ngoài
docker exec "$CONTAINER_NAME" mysqldump -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$FILE_PATH"

if [ $? -eq 0 ]; then
    echo "[$((date))]: Backup thành công! File lưu tại: $FILE_PATH"
    
    # Nén file lại để tiết kiệm dung lượng (.gz)
    gzip "$FILE_PATH"
    echo "[$((date))]: Đã nén thành công: ${FILE_PATH}.gz"
    
    # Tự động xóa các file backup cũ hơn 30 ngày để tránh đầy bộ nhớ
    find "$BACKUP_DIR" -type f -name "*.sql.gz" -mtime +30 -delete
    echo "[$((date))]: Đã dọn dẹp các bản backup cũ hơn 30 ngày."
else
    echo "[$((date))]: LỖI! Không thể backup database."
    exit 1
fi
