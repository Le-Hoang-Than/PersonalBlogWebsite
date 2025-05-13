Thành phần tái sử dụng:
- /components/                  : Navbar, footer, form feedback dùng chung
- /assets/css/                  : CSS từng phần + style chung
- /assets/js/                   : Script JS từng phần + script chung
- /data/tags.json               : Metadata bài viết (title, path, tags...)

Công nghệ:
- HTML, CSS, JavaScript
- jQuery (cho feedback và xử lý dữ liệu từ JSON)
- JSON (lưu metadata bài viết)

Ghi chú:
- Sử dụng JavaScript hoặc jQuery để load nội dung động từ `tags.json`
- Có thể mở rộng với các framework như React, Vue trong tương lai nếu cần



<pre>```
/PersonalBlogWebsite
└── /blog
    ├── admin/
    │        │
    │        ├── index.php                  # Trang dashboard chính (nếu có)
    │        ├── login.php                  # Giao diện đăng nhập
    │        ├── logout.php                 # Xử lý đăng xuất
    │        │
    │        ├── dashboard/                 # Các trang điều khiển chính
    │        │   ├── index.php              # Tổng quan (dashboard)
    │        │   └── stats.php              # Thống kê truy cập, bài viết, phản hồi
    │        │
    │        ├── posts/                     # Quản lý bài viết
    │        │   ├── index.php              # Danh sách bài viết
    │        │   ├── create.php             # Thêm bài viết
    │        │   ├── edit.php               # Sửa bài viết
    │        │   └── delete.php             # Xóa bài viết
    │        │
    │        ├── feedbacks/                 # Quản lý phản hồi người dùng
    │        │   ├── index.php              # Danh sách phản hồi
    │        │   └── view.php               # Xem chi tiết phản hồi
    │        │
    │        ├── users/                     # Quản lý người dùng admin (nếu có nhiều người)
    │        │   ├── index.php
    │        │   ├── create.php
    │        │   ├── edit.php
    │        │   └── delete.php
    │        │
    │        ├── assets/                    # Tài nguyên riêng cho trang admin
    │        │   ├── css/
    │        │   ├── js/
    │        │   └── images/
    │        │
    │        └── includes/                  # Các file dùng chung
    │            ├── header.php             # Phần đầu trang
    │            ├── footer.php             # Phần cuối trang
    │            ├── sidebar.php            # Thanh điều hướng
    │            └── auth.php               # Kiểm tra đăng nhập
    │
    ├── index.html                             # Trang chủ
    │
    ├── /news                                  # Tin tức / cập nhật
    │   └── index.html         
    │           
    ├── /tools                                 # Các công cụ tự viết hoặc tổng hợp
    │   └── index.html          
    │          
    ├── /ctf                                   # CTF writeups
    │   └── index.html  
    │                  
    ├── /my-roadmap                             # Lộ trình học tập cá nhân
    │   └── index.html 
    │                   
    ├── /portfolio                             # Portfolio cá nhân
    │   └── index.html                    
    │
    ├── /topics                               # Chuyên mục kiến thức nền tảng
    │   ├── index.html                        # Trang chọn category
    │   │
    │   ├── /network
    │   │   ├── index.html                    # Chọn tag (VD: TCP, DNS...)
    │   │   ├── tcp.html
    │   │   ├── dns.html
    │   │   └── network.css / network.js
    │   │
    │   ├── /security
    │   │   ├── index.html
    │   │   ├── xss.html
    │   │   ├── csrf.html
    │   │   └── security.css / security.js
    │   │
    │   ├── /cryptography
    │   │   ├── index.html
    │   │   ├── aes.html
    │   │   ├── rsa.html
    │   │   └── crypto.css / crypto.js
    │   │
    │   ├── /mathematics
    │   │   ├── index.html
    │   │   ├── calculus.html
    │   │   ├── discrete.html
    │   │   └── math.css / math.js
    │   │
    │   └── /operating-systems
    │       ├── index.html
    │       ├── memory.html
    │       ├── filesystem.html
    │       └── os.css / os.js
    │
    ├── /components
    │   ├── navbar.html
    │   ├── footer.html
    │   ├── navbar-left.html        # Navbar bên trái
    │   ├── navbar-right.html       # Navbar bên phải
    │   └── feedback-form.html      # Phần feedback
    │
    ├── /assets
    │   ├── /css
    │   │   ├── /common                   # sCSS chung toàn site
    │   │   │   └── style.scss
    │   │   ├── /news                                  
    │   │   │   ├── news.scss                    
    │   │   │   └── _responsive-news.scss                    
    │   │   ├── /tools                                 
    │   │   │   ├── tools.scss                    
    │   │   │   └── _responsive-tools.scss                    
    │   │   ├── /ctf                                   
    │   │   │   ├── ctf.scss                    
    │   │   │   └── _responsive-ctf.scss                    
    │   │   ├── /my-roadmap                             
    │   │   │   ├── my-roadmap.scss                    
    │   │   │   └── _responsive-my-roadmap.scss                    
    │   │   └── /portfolio                             
    │   │       ├── portfolio.scss
    │   │       └── _responsive-portfolio.scss
    │   │
    │   ├── /js
    │   │   ├── 
    │   │   ├── navbar.js
    │   │   ├── footer.js
    │   │   ├── feedback.js
    │   │   ├── news.js
    │   │   └── roadmap.js
    │   │   ├── /common                   # js chung toàn site
    │   │   │   ├── script.js                 # JS chung (navbar, darkmode,...)
    │   │   │   ├── navbar.js
    │   │   │   ├── footer.js
    │   │   │   └── feedback.js
    │   │   ├── /news                                  
    │   │   │   └── news.js                    
    │   │   ├── /tools                                 
    │   │   │   └── tools.js                    
    │   │   ├── /ctf                                   
    │   │   │   └── ctf.js                    
    │   │   ├── /my-roadmap                             
    │   │   │   └── my-roadmap.js                    
    │   │   └── /portfolio                             
    │   │       └── portfolio.js
    │   │
    │   ├── /icons
    │   │   └── blog.icon
    │   │
    │   ├── /fonts
    │   │   ├── /DancingScript                                  
    │   │   └── /Hack                                 
    │   │
    │   ├── /videos
    │   │
    │   └── /images
    │       ├── /news                                  
    │       │           
    │       ├── /tools                                 
    │       │          
    │       ├── /ctf                                   
    │       │                  
    │       ├── /my-roadmap                             
    │       │                   
    │       ├── /portfolio                            
    │       │
    │       └── /topics                               
    │
    ├── /data
    │   └── tags.json                     # Metadata bài viết: title, slug, path, tags
    │
    └── feedback.html                     # (tùy chọn) trang feedback độc lập
</pre>


admin/
│
├── auth/                      # Các chức năng xác thực
│   ├── login.php             # Trang đăng nhập (form)
│   ├── logout.php            # Đăng xuất
│   └── process-login.php     # Xử lý đăng nhập
│
├── dashboard/                # Giao diện chính của admin sau khi đăng nhập
│   └── index.php             # Dashboard chính
│
├── feedback/                 # Quản lý phản hồi từ người dùng
│   ├── index.php             # Hiển thị danh sách phản hồi
│   ├── view.php              # Xem chi tiết phản hồi
│   ├── delete.php            # Xóa phản hồi
│   └── verify.php            # Xác thực phản hồi nếu cần
│
├── posts/                    # Quản lý bài viết
│   ├── index.php             # Danh sách bài viết
│   ├── create.php            # Giao diện thêm bài viết
│   ├── edit.php              # Giao diện sửa bài viết
│   ├── delete.php            # Xóa bài viết
│   └── store.php             # Lưu bài viết mới hoặc cập nhật
│
├── users/                    # Quản lý tài khoản admin (nếu có nhiều người)
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   └── delete.php
│
├── components/               # Các thành phần dùng lại trong nhiều trang
│   ├── header.php
│   ├── footer.php
│   ├── sidebar.php
│   └── navbar.php
│
├── css/                      # Giao diện admin (nếu khác frontend)
│   └── admin.css
│
├── js/                       # JavaScript cho giao diện admin
│   └── admin.js
│
├── assets/                   # Hình ảnh hoặc tài nguyên riêng cho admin
│   └── images/
│
└── index.php                 # Redirect hoặc Dashboard mặc định





admin/
├── auth/
│   ├── login.php
│   ├── send-token.php
│   └── verify-token.php
│
└── dashboard/
    ├── index.php          <-- Trang chính (dashboard)
    ├── partials/
    │   ├── header.php     <-- Navbar / Header chung
    │   ├── sidebar.php    <-- Menu bên trái
    │   ├── footer.php     <-- Footer
    │   └── auth-check.php <-- Kiểm tra đăng nhập
    ├── assets/
    │   ├── css/
    │   ├── images/
    │   └── js/
    ├── posts/                    # Quản lý bài viết theo curd
    │   ├── index.php             # Danh sách bài viết
    │   ├── create.php            # Giao diện thêm bài viết
    │   ├── edit.php              # Giao diện sửa bài viết
    │   ├── delete.php            # Xóa bài viết
    │   └── store.php             # Lưu bài viết mới hoặc cập nhật
    └── categories/                    # Quản lý bài viết theo curd
        ├── index.php             # Danh sách bài viết
        ├── create.php            # Giao diện thêm bài viết
        ├── edit.php              # Giao diện sửa bài viết
        ├── delete.php            # Xóa bài viết
        └── store.php             # Lưu bài viết mới hoặc cập nhật

-- --------------------------------------------------------
-- 1. Bảng admins (Quản trị viên)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `avatar_path` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE UNIQUE INDEX `idx_admins_email` ON `admins` (`email`);

-- --------------------------------------------------------
-- 2. Bảng admin_tokens (Xác thực Admin)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_tokens` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `revoked` BOOLEAN DEFAULT FALSE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 3. Bảng categories (Danh mục)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `parent_id` INT DEFAULT NULL,
  `visible` BOOLEAN DEFAULT TRUE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE UNIQUE INDEX `idx_categories_slug` ON `categories` (`slug`);

-- --------------------------------------------------------
-- 4. Bảng posts (Bài viết)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `posts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `category_id` INT DEFAULT NULL,
  `admin_id` INT NOT NULL,
  `thumbnail_path` VARCHAR(255) DEFAULT NULL,
  `content_html` TEXT NOT NULL,
  `toc_json` TEXT DEFAULT NULL,
  `is_published` BOOLEAN DEFAULT FALSE,
  `published_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  `purge_method` ENUM('immediate', 'after_30_days') DEFAULT NULL,
  `scheduled_purge_date` DATETIME DEFAULT NULL,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE UNIQUE INDEX `idx_posts_slug` ON `posts` (`slug`);
CREATE INDEX `idx_posts_deleted` ON `posts` (`deleted_at`);

-- --------------------------------------------------------
-- 5. Bảng feedbacks (Phản hồi người dùng)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` BOOLEAN DEFAULT FALSE,
  `is_verified` BOOLEAN DEFAULT FALSE COMMENT 'Người dùng đã xác nhận email qua link chưa',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 6. Bảng feedback_tokens (Xác thực phản hồi)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `feedback_tokens` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `feedback_id` INT NOT NULL,
  `token` VARCHAR(255) NOT NULL UNIQUE,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 7. Bảng trash_bin (Kho lưu trữ tạm thời)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `trash_bin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `item_id` INT NOT NULL,
  `table_name` VARCHAR(50) NOT NULL COMMENT 'posts/categories/feedbacks',
  `data_json` JSON NOT NULL COMMENT 'Lưu toàn bộ data dạng JSON',
  `purge_method` ENUM('immediate', 'after_30_days') NOT NULL,
  `scheduled_purge_date` DATETIME NOT NULL,
  `deleted_by` INT NOT NULL COMMENT 'Admin ID',
  `deleted_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`deleted_by`) REFERENCES `admins`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 8. Bảng audit_logs (Lịch sử thao tác)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT NOT NULL,
  `action` VARCHAR(50) NOT NULL COMMENT 'create/update/delete/restore',
  `table_name` VARCHAR(50) NOT NULL,
  `record_id` INT NOT NULL,
  `old_value` JSON DEFAULT NULL,
  `new_value` JSON DEFAULT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 9. Cron Job: Tự động xóa sau 30 ngày
-- --------------------------------------------------------
DELIMITER //
CREATE EVENT IF NOT EXISTS `auto_purge_trash`
ON SCHEDULE EVERY 1 DAY
DO
BEGIN
  -- Xóa khỏi trash_bin
  DELETE FROM trash_bin 
  WHERE scheduled_purge_date <= NOW();

  -- Xóa bài viết bị đánh dấu purge
  DELETE FROM posts 
  WHERE purge_method = 'after_30_days' 
  AND scheduled_purge_date <= NOW();
END//
DELIMITER ;
