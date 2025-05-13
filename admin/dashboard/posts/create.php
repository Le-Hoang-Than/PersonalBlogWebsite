<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trình Tạo Bài Viết</title>
    <style>
        /* Reset CSS cơ bản */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .editor-container {
            display: flex;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        .components-sidebar {
            width: 250px;
            background: #f5f5f5;
            padding: 20px;
            border-right: 1px solid #ddd;
        }

        .component-type {
            padding: 12px;
            margin-bottom: 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .component-type:hover {
            background: #f0f0f0;
            transform: translateX(5px);
        }

        .content-zone {
            flex: 1;
            padding: 20px;
            background: white;
        }

        .content-block {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 4px;
            position: relative;
        }

        .content-block:hover {
            border-color: #2196F3;
        }

        .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #ff4444;
            color: white;
            border: none;
            padding: 2px 8px;
            border-radius: 50%;
            cursor: pointer;
            display: none;
        }

        .content-block:hover .delete-btn {
            display: block;
        }

        .placeholder {
            color: #999;
            text-align: center;
            padding: 50px 20px;
        }

        .input-field {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .image-preview {
            max-width: 100%;
            margin-top: 10px;
            display: none;
        }

        /* Thêm style cho các thẻ heading */
        .content-block h1,
        .content-block h2,
        .content-block h3 {
            width: 100%;
            padding: 10px;
            border: 1px solid #eee;
            margin: 10px 0;
            outline: none;
            font-family: Arial, sans-serif;
        }

        .content-block h1 {
            font-size: 2em;
            color: #333;
        }

        .content-block h2 {
            font-size: 1.5em;
            color: #444;
        }

        .content-block h3 {
            font-size: 1.2em;
            color: #666;
        }
        /* Thêm style cho phần heading có thể chỉnh sửa */
    .editable-heading {
        border: 1px solid transparent;
        padding: 10px;
        margin: 10px 0;
        transition: all 0.3s;
    }

    .editable-heading:focus {
        outline: none;
        border-color: #2196F3;
        background: #f8f8f8;
    }

    .editable-heading[contenteditable="true"]:empty::before {
        content: attr(data-placeholder);
        color: #999;
    }
    </style>
</head>

<body>
    <div class="editor-container">
        <!-- Sidebar chứa các thành phần -->
        <aside class="components-sidebar">
            <div class="component-type" data-type="h1">Tiêu đề H1</div>
            <div class="component-type" data-type="h2">Tiêu đề H2</div>
            <div class="component-type" data-type="h3">Tiêu đề H3</div>
            <div class="component-type" data-type="h4">Tiêu đề H4</div>
            <div class="component-type" data-type="h5">Tiêu đề H5</div>
            <div class="component-type" data-type="h6">Tiêu đề H6</div>
            <div class="component-type" data-type="text">Đoạn văn</div>
            <div class="component-type" data-type="image">Hình ảnh</div>
        </aside>

        <!-- Khu vực nội dung chính -->
        <main class="content-zone" id="contentZone">
            <div class="placeholder">Kích vào các thành phần bên trái để thêm vào bài viết</div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const contentZone = document.getElementById('contentZone');
        const componentTypes = document.querySelectorAll('.component-type');

        // Xử lý click vào các thành phần
        componentTypes.forEach(component => {
            component.addEventListener('click', () => {
                const type = component.dataset.type;
                createNewComponent(type);
            });
        });

        // Tạo component mới
        function createNewComponent(type) {
            const wrapper = document.createElement('div');
            wrapper.className = 'content-block';

            // Nút xóa
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'delete-btn';
            deleteBtn.innerHTML = '×';
            deleteBtn.onclick = () => wrapper.remove();

            // Nội dung chính
            let content;
            switch (type) {
                case 'h1':
                case 'h2':
                case 'h3':
                case 'h4':
                case 'h5':
                case 'h6':
                    // Tạo thẻ heading với placeholder
                    content = document.createElement(type);
                    content.textContent = `Tiêu đề ${type.toUpperCase()}`;
                    content.setAttribute('contenteditable', 'true');
                    content.classList.add('editable-heading');
                    break;
                    
                case 'text':
                    content = document.createElement('textarea');
                    content.className = 'input-field';
                    content.placeholder = 'Nhập nội dung...';
                    content.rows = 4;
                    break;

                case 'image':
                    content = document.createElement('div');
                    content.innerHTML = `
                        <input type="text" class="input-field image-url" placeholder="Dán URL hình ảnh...">
                        <input type="file" class="image-upload" accept="image/*">
                        <img class="image-preview">
                    `;
                    break;
            }

            // Thêm vào DOM
            wrapper.appendChild(deleteBtn);
            wrapper.appendChild(content);
            contentZone.querySelector('.placeholder')?.remove();
            contentZone.appendChild(wrapper);

            // Xử lý hình ảnh
            if (type === 'image') {
                handleImageInput(wrapper);
            }

            // Focus vào nội dung khi tạo mới
            if (type.startsWith('h')) {
                content.focus();
            }
        }

            // Xử lý upload hình ảnh
            function handleImageInput(wrapper) {
                const urlInput = wrapper.querySelector('.image-url');
                const fileInput = wrapper.querySelector('.image-upload');
                const preview = wrapper.querySelector('.image-preview');

                // Xử lý nhập URL
                urlInput.addEventListener('input', () => {
                    preview.src = urlInput.value;
                    preview.style.display = urlInput.value ? 'block' : 'none';
                });

                // Xử lý upload file
                fileInput.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</body>

</html>