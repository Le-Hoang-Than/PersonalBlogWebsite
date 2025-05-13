<?php 
include '../partials/header.php'; 
require_once '../../../core/config/db.php';

// Lấy danh sách tất cả danh mục (gọi stored procedure)
$stmt = $pdo->prepare("CALL get_all_categories()");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor(); // Giải phóng result set để gọi tiếp SP khác

// Lấy danh mục cha
$stmt = $pdo->prepare("CALL get_parent_categories()");
$stmt->execute();
$parentCategoriesRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor(); // Giải phóng tiếp

// Tạo mảng id => name cho danh mục cha
$parentCategories = [];
foreach ($parentCategoriesRaw as $row) {
    $parentCategories[$row['id']] = $row['name'];
}
?>
<main id="Main-content">
    <?php include '../partials/sidebar.php'; ?>

    <div class="container">
        <h2>Quản lý danh mục</h2>
        <a href="create.php" class="btn btn-primary">+ Thêm danh mục</a>

        <!-- Bảng danh mục -->
        <table class="category-table">
            <thead>
                <tr>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Danh mục cha</th>
                    <th>Hiển thị</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= htmlspecialchars($category['name']) ?></td>
                        <td><?= htmlspecialchars($category['slug']) ?></td>
                        <td>
                            <?php
                            // Lấy tên danh mục cha từ mảng đã lưu
                            $parentName = $category['parent_category_id'] ? (htmlspecialchars($parentCategories[$category['parent_category_id']]) ?? 'Không có') : 'Không có';
                            echo $parentName;
                            ?>
                        </td>
                        <td><?= $category['is_visible'] ? '✔' : '✖' ?></td>
                        <td><?= htmlspecialchars($category['created_at']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $category['id'] ?>" class="btn btn-sm">Sửa</a>
                            <a href="delete.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include '../partials/footer.php'; ?>
