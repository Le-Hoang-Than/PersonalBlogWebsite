<?php
require_once '../../../core/config/db.php';

// Lấy danh sách category
$stmt = $pdo->prepare("CALL get_all_categories()");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$selectedCategoryId = $_GET['category_id'] ?? null;
$selectedTopicId = $_GET['topic_id'] ?? null;
$selectedChapterId = $_GET['chapter_id'] ?? null;

// Nếu có category_id, load topics
$topics = [];
if ($selectedCategoryId) {
    $stmt = $pdo->prepare("CALL get_topics_by_category_id(:category_id)");
    $stmt->execute(['category_id' => $selectedCategoryId]);
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
}

// Nếu có topic_id, load chapters
$chapters = [];
if ($selectedTopicId) {
    $stmt = $pdo->prepare("CALL get_chapters_by_topic(:topic_id)");
    $stmt->execute(['topic_id' => $selectedTopicId]);
    $chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
}
?>
<?php include '../partials/header.php'; ?>

<main id="Main-content">
    <?php include '../partials/sidebar.php'; ?>

    <div class="container">
        <h2>Tạo bài viết mới</h2>

<form method="POST" action="store.php" enctype="multipart/form-data" id="postForm">
    <div class="form-group">
        <label for="category">Danh mục:</label>
        <select name="category_id" id="category" class="form-control" required>
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $selectedCategoryId == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="topic">Chủ đề:</label>
        <select name="topic_id" id="topic" class="form-control" <?= $selectedCategoryId ? '' : 'disabled' ?> required>
            <option value="">-- Chọn chủ đề --</option>
            <?php foreach ($topics as $topic): ?>
                <option value="<?= $topic['id'] ?>" <?= $selectedTopicId == $topic['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($topic['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="chapter">Chương:</label>
        <select name="chapter_id" id="chapter" class="form-control" <?= $selectedTopicId ? '' : 'disabled' ?>>
            <option value="">-- Chọn chương (tuỳ chọn) --</option>
            <?php foreach ($chapters as $ch): ?>
                <option value="<?= $ch['id'] ?>" <?= $selectedChapterId == $ch['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ch['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="title">Tiêu đề bài viết:</label>
        <input type="text" name="title" id="title" class="form-control" required placeholder="Nhập tiêu đề bài viết" />
    </div>

    <div id="editorjs"></div>
    <input type="hidden" name="content" id="contentInput" />

    <div class="form-group">
        <button type="submit" class="btn">Lưu bài viết</button>
    </div>
</form>
    </div>
</main>

<!-- Các script editor.js và các plugin -->
<script src="/PersonalBlogWebsite/admin/dashboard/assets/js/editorjs/editor.js"></script>
<script src="/PersonalBlogWebsite/admin/dashboard/assets/js/editorjs/header.js"></script>
<script src="/PersonalBlogWebsite/admin/dashboard/assets/js/editorjs/list.js"></script>
<script src="/PersonalBlogWebsite/admin/dashboard/assets/js/editorjs/quote.js"></script>
<script src="/PersonalBlogWebsite/admin/dashboard/assets/js/editorjs/marker.js"></script>
<script src="/PersonalBlogWebsite/admin/dashboard/assets/js/editorjs/table.js"></script>
<script src="/PersonalBlogWebsite/admin/dashboard/assets/js/editorjs/image.js"></script>
<script src="/PersonalBlogWebsite/admin/dashboard/assets/js/editorjs/link.js"></script>
<script>
    const editor = new EditorJS({
        holder: 'editorjs',
        placeholder: 'Nhập nội dung bài viết...',
        tools: {
            header: Header,
            list: List,
            quote: Quote,
            marker: Marker,
            table: Table,
            linkTool: {
                class: LinkTool,
                config: {
                    endpoint: 'http://your-server.com/fetchUrl'
                }
            },
            image: {
                class: ImageTool,
                config: {
                    uploader: {
                        uploadByFile(file) {
                            const formData = new FormData();
                            formData.append('image', file);
                            return fetch('/PersonalBlogWebsite/admin/dashboard/posts/imageUpload.php', {
                                method: 'POST',
                                body: formData
                            }).then(res => res.json()).then(data => {
                                if (data.success) {
                                    return {
                                        success: 1,
                                        file: { url: data.file.url }
                                    };
                                } else {
                                    return Promise.reject(data.message || 'Upload failed');
                                }
                            }).catch(() => Promise.reject('Upload error'));
                        },
                        uploadByUrl(url) {
                            return Promise.resolve({
                                success: 1,
                                file: { url: url }
                            });
                        }
                    }
                }
            }
        }
    });

    document.getElementById('postForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const title = document.getElementById('title').value.trim();

        try {
            const outputData = await editor.save();
            const contentJSON = JSON.stringify(outputData);

            if (title === '') {
                alert('Tiêu đề bài viết không được để trống.');
                return;
            }

            if (!outputData.blocks || outputData.blocks.length === 0) {
                alert('Nội dung bài viết không được để trống.');
                return;
            }

            // Nếu topic bị disabled hoặc không chọn, reset value về rỗng
            if (document.getElementById('topic').disabled || document.getElementById('topic').value === '') {
                document.getElementById('topic').value = '';
            }

            // Nếu chapter bị disabled hoặc không chọn, reset value về rỗng
            if (document.getElementById('chapter').disabled || document.getElementById('chapter').value === '') {
                document.getElementById('chapter').value = '';
            }

            document.getElementById('contentInput').value = contentJSON;
            e.target.submit();

        } catch (error) {
            alert('Lỗi khi lưu nội dung bài viết: ' + error);
        }
    });

    // Thêm xử lý enable/disable topic khi chọn category
    document.getElementById('category').addEventListener('change', function () {
        const topicSelect = document.getElementById('topic');
        const chapterSelect = document.getElementById('chapter');

        if (this.value) {
            topicSelect.disabled = false;
        } else {
            topicSelect.value = '';
            topicSelect.disabled = true;
            chapterSelect.value = '';
            chapterSelect.disabled = true;
        }
        // Trigger load lại trang với category_id mới để lấy topics
        // Nếu bạn muốn làm ajax thì cần thêm code
        window.location.href = `create.php?category_id=${this.value}`;
    });

    // Enable/disable chapter khi chọn topic
    document.getElementById('topic').addEventListener('change', function () {
        const chapterSelect = document.getElementById('chapter');
        if (this.value) {
            chapterSelect.disabled = false;
            // Trigger load lại trang với category_id và topic_id mới
            const categoryId = document.getElementById('category').value;
            window.location.href = `create.php?category_id=${categoryId}&topic_id=${this.value}`;
        } else {
            chapterSelect.value = '';
            chapterSelect.disabled = true;
            const categoryId = document.getElementById('category').value;
            window.location.href = `create.php?category_id=${categoryId}`;
        }
    });
</script>
<?php include '../partials/footer.php'; ?>