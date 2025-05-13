-- -------------------------------
-- blog database
-- -------------------------------
DROP DATABASE IF EXISTS blog;

CREATE DATABASE blog;

USE blog;

-- -------------------------------
-- 1. Bảng admin
-- -------------------------------
CREATE TABLE IF NOT EXISTS `admin` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `avatar_image_path` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- Thêm chỉ mục cho cột email
CREATE INDEX idx_email ON admin(email);

-- Thêm admin mẫu
INSERT INTO
    `admin` (email, password, name, avatar_image_path)
VALUES
    (
        'lehoangthan.blog@gmail.com',
        SHA2('DH52201426', 256),
        'Lê Hoàng Thân',
        '/images/admin-avatar.png'
    );

-- Thủ tục kiểm tra tài khoản
DELIMITER / / CREATE PROCEDURE CheckAdminCredentials (
    IN in_email VARCHAR(255),
    IN in_password VARCHAR(255)
) BEGIN IF EXISTS (
    SELECT
        1
    FROM
        admin
    WHERE
        email = in_email
        AND password = SHA2(in_password, 256)
) THEN
SELECT
    TRUE AS is_valid;

ELSE
SELECT
    FALSE AS is_valid;

END IF;

END / / DELIMITER;

-- -------------------------------
-- 2. Bảng admin_auth_token
-- -------------------------------
CREATE TABLE IF NOT EXISTS `admin_auth_token` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admin (id) ON DELETE CASCADE,
    INDEX (expires_at)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- Bật Event Scheduler nếu chưa bật
SET
    GLOBAL event_scheduler = ON;

-- Tạo event dọn dẹp token hết hạn
CREATE EVENT IF NOT EXISTS ev_cleanup_expired_tokens ON SCHEDULE EVERY 5 MINUTE DO
DELETE FROM
    admin_auth_token
WHERE
    expires_at < NOW();

-- Thủ tục tạo token mới
DELIMITER / / CREATE PROCEDURE CreateAdminToken (
    IN in_admin_id INT,
    IN in_token_hash VARCHAR(255)
) BEGIN -- Xoá token cũ
DELETE FROM
    admin_auth_token
WHERE
    admin_id = in_admin_id;

-- Tạo token mới với thời gian hết hạn là 15 phút
INSERT INTO
    admin_auth_token (admin_id, token_hash, expires_at)
VALUES
    (
        in_admin_id,
        in_token_hash,
        NOW() + INTERVAL 180 MINUTE
    );

END / / DELIMITER;

-- Thủ tục xác minh token
DELIMITER / / CREATE PROCEDURE VerifyAdminToken (
    IN in_email VARCHAR(255),
    IN in_token VARCHAR(255)
) BEGIN DECLARE v_admin_id INT DEFAULT NULL;

DECLARE v_token_hash VARCHAR(255);

DECLARE v_expires_at DATETIME;

-- Kiểm tra admin có tồn tại
SELECT
    id INTO v_admin_id
FROM
    admin
WHERE
    email = in_email
LIMIT
    1;

IF v_admin_id IS NULL THEN
SELECT
    FALSE AS is_valid,
    NULL AS admin_id;

ELSE -- Lấy token tương ứng
SELECT
    token_hash,
    expires_at INTO v_token_hash,
    v_expires_at
FROM
    admin_auth_token
WHERE
    admin_id = v_admin_id
LIMIT
    1;

IF v_token_hash IS NOT NULL
AND v_expires_at > NOW()
AND SHA2(in_token, 256) = v_token_hash THEN
SELECT
    TRUE AS is_valid,
    v_admin_id AS admin_id;

ELSE
SELECT
    FALSE AS is_valid,
    NULL AS admin_id;

END IF;

END IF;

END / / DELIMITER;

-- Thủ tục xóa token
DELIMITER / / CREATE PROCEDURE DeleteAdminToken (IN in_admin_id INT) BEGIN
DELETE FROM
    admin_auth_token
WHERE
    admin_id = in_admin_id;

END / / DELIMITER;

-- -------------------------------
-- 3. Bảng category
-- -------------------------------
CREATE TABLE IF NOT EXISTS `category` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `parent_category_id` INT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    FOREIGN KEY (`parent_category_id`) REFERENCES `category` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- Tối ưu truy vấn theo phân cấp
CREATE INDEX idx_parent_category_id ON category(parent_category_id);

-- Lấy danh mục cha (parent_category_id IS NULL)
DELIMITER / / CREATE PROCEDURE get_parent_categories() BEGIN
SELECT
    id,
    name
FROM
    category
WHERE
    parent_category_id IS NULL;

END / / DELIMITER;

--Lấy tất cả danh mục
DELIMITER / / CREATE PROCEDURE get_all_categories() BEGIN
SELECT
    *
FROM
    category
WHERE
    deleted_at IS NULL
ORDER BY
    parent_category_id ASC;

END / / DELIMITER;

-- Kiểm tra slug đã tồn tại chưa
DELIMITER / / CREATE PROCEDURE check_slug_exists(IN input_slug VARCHAR(255)) BEGIN
SELECT
    COUNT(*) AS slug_count
FROM
    category
WHERE
    slug = input_slug;

END / / DELIMITER;

-- Chèn danh mục mới
DELIMITER //

CREATE PROCEDURE insert_category(
    IN input_name VARCHAR(255),
    IN input_slug VARCHAR(255),
    IN input_parent_id INT
)
BEGIN
    INSERT INTO category (name, slug, parent_category_id)
    VALUES (input_name, input_slug, input_parent_id);
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE delete_category(IN in_category_id INT)
BEGIN
    -- Xóa tất cả các bài viết liên quan đến danh mục này
    DELETE FROM post WHERE category_id = in_category_id;

    -- Xóa danh mục
    DELETE FROM category WHERE id = in_category_id;

END //

DELIMITER ;

-- -------------------------------
-- 4. Bảng post
-- -------------------------------
CREATE TABLE IF NOT EXISTS `post` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `category_id` INT DEFAULT NULL,
    `admin_id` INT NOT NULL,
    `thumbnail_image_path` VARCHAR(255) DEFAULT NULL,
    `content_html` TEXT NOT NULL,
    `toc_json` JSON DEFAULT NULL,
    `is_published` BOOLEAN DEFAULT FALSE,
    `published_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    `purge_method` ENUM ('manual', 'auto') DEFAULT NULL,
    `purge_scheduled_date` DATETIME DEFAULT NULL,
    FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE
    SET
        NULL,
        FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- -------------------------------
-- 5. Bảng feedback
-- -------------------------------
CREATE TABLE IF NOT EXISTS `feedback` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` BOOLEAN DEFAULT FALSE,
    `is_verified` BOOLEAN DEFAULT FALSE,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- -------------------------------
-- 6. Bảng feedback_verify_token
-- -------------------------------
CREATE TABLE IF NOT EXISTS `feedback_verify_token` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `feedback_id` INT NOT NULL,
    `verification_token` VARCHAR(255) NOT NULL UNIQUE,
    `expires_at` DATETIME NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`feedback_id`) REFERENCES `feedback` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- -------------------------------
-- 7. Bảng temporary_storage (lưu trữ mềm)
-- -------------------------------
CREATE TABLE IF NOT EXISTS `temporary_storage` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `original_id` INT NOT NULL,
    `original_table` VARCHAR(255) NOT NULL,
    `deleted_data` JSON NOT NULL,
    `purge_method` ENUM ('manual', 'auto') NOT NULL,
    `purge_scheduled_date` DATETIME NOT NULL,
    `deleted_by` INT NOT NULL,
    `deleted_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`deleted_by`) REFERENCES `admin` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- -------------------------------
-- 8. Bảng activity_log
-- -------------------------------
CREATE TABLE IF NOT EXISTS `activity_log` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `admin_id` INT DEFAULT NULL,
    `action` VARCHAR(100) NOT NULL,
    `table_name` VARCHAR(255) NOT NULL,
    `record_id` INT NOT NULL,
    `old_data` JSON DEFAULT NULL,
    `new_data` JSON DEFAULT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- -------------------------------
-- 9. Sự kiện tự động xóa dữ liệu sau thời gian purge
-- -------------------------------
DELIMITER / / CREATE EVENT IF NOT EXISTS `auto_purge_data` ON SCHEDULE EVERY 1 DAY DO BEGIN -- Xóa dữ liệu khỏi temporary_storage
DELETE FROM
    `temporary_storage`
WHERE
    `purge_scheduled_date` <= NOW ();

-- Xóa các post có purge_method = 'auto'
DELETE FROM
    `post`
WHERE
    `purge_method` = 'auto'
    AND `purge_scheduled_date` <= NOW ();

END / / DELIMITER;