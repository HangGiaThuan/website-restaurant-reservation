# Hướng Dẫn Cài Đặt và Mở Trang Web

Đây là hướng dẫn giúp bạn cài đặt và chạy website đặt bàn nhà hàng trên máy tính cá nhân.

## Yêu cầu

- Máy tính có cài XAMPP, WAMP, hoặc LAMP (có Apache, PHP, MySQL)
- MySQL đã chạy và sẵn sàng sử dụng

## Các bước cài đặt

1. **Nhập cơ sở dữ liệu**
Cách 1:
Mở command line hoặc terminal, điều hướng tới thư mục chứa file `restaurant.sql`, chạy lệnh:

bash
mysql -u root -p < restaurant.sql

Thay `root` bằng username database của bạn nếu khác. Nhập mật khẩu MySQL khi yêu cầu.

Cách 2:
import file `restaurant.sql` trên phpMyAdmin sau đó database sẽ được tạo

2. **Cấu hình kết nối database**

Mở file `includes/db.php`, sửa biến `$username` và `$password` cho đúng với thông tin MySQL của bạn.

php
$host = 'localhost';
$dbname = 'restaurant_db';
$username = 'tên_user_mysql_của_bạn';
$password = 'mật_khẩu_mysql_của_bạn';

3. **Đặt dự án vào thư mục web server**

Copy toàn bộ thư mục `restaurant-reservation` vào thư mục web root của server, ví dụ:

- XAMPP: `C:\xampp\htdocs\`
- WAMP: `C:\wamp\www\`
- LAMP: `/var/www/html/`

4. **Phân quyền cho folder ảnh**

Đảm bảo thư mục `assets/images/` có quyền ghi (write) để upload ảnh từ trang quản trị.

5. **Mở trình duyệt và truy cập**

Gõ địa chỉ sau vào trình duyệt:

http://localhost/restaurant-reservation/index.php

6. **Đăng nhập quản trị**

Dùng tài khoản mặc định để đăng nhập vào trang quản trị:

- Tên đăng nhập: `admin`
- Mật khẩu: `admin123`

## Ghi chú

- Sau khi đăng nhập, bạn có thể quản lý menu món ăn, xem và duyệt đặt bàn trong phần quản trị.
- Mặc định đặt bàn khi tạo có trạng thái "pending" (chờ duyệt).
- Giao diện và hệ thống được xây dựng bằng PHP thuần, dễ dàng tùy biến và mở rộng.

Chúc bạn thành công và dùng website hiệu quả!
