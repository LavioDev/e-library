# Cấu hình
$BackupDir = "./backups"
$DbName = "e_library"
$ContainerName = "e_library_mysql"
$DbUser = "root"
$DbPass = "root"

# Tạo thư mục nếu chưa tồn tại
if (!(Test-Path $BackupDir)) {
    New-Item -ItemType Directory -Force -Path $BackupDir | Out-Null
}

$Timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$FileName = "backup_${DbName}_${Timestamp}.sql"
$FilePath = Join-Path $BackupDir $FileName

Write-Host "Đang tiến hành backup database '$DbName' từ container '$ContainerName'..."

# Thực hiện lệnh mysqldump
docker exec $ContainerName mysqldump -u$DbUser -p$DbPass $DbName > $FilePath

if ($LASTEXITCODE -eq 0) {
    Write-Host "Backup thành công! File lưu tại: $FilePath" -ForegroundColor Green
} else {
    Write-Error "Lỗi! Không thể kết xuất backup từ MySQL container."
}
