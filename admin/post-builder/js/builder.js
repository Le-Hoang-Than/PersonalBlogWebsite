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
        switch(type) {
            case 'h1':
            case 'h2':
            case 'h3':
                content = document.createElement('input');
                content.type = 'text';
                content.className = `input-field ${type}`;
                content.placeholder = `Nhập ${component.textContent}...`;
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
        if(type === 'image') {
            handleImageInput(wrapper);
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
            if(file) {
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