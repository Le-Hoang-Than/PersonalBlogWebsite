const tbody = document.getElementById('sortable-category');
let draggingRow = null;

tbody.addEventListener('dragstart', (e) => {
    draggingRow = e.target;
});

tbody.addEventListener('dragover', (e) => {
    e.preventDefault();
    const target = e.target.closest('tr');
    if (target && target !== draggingRow) {
        const rect = target.getBoundingClientRect();
        const next = (e.clientY - rect.top) > (rect.height / 2);
        tbody.insertBefore(draggingRow, next ? target.nextSibling : target);
    }
});
