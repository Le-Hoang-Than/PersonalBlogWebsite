const sidebar = document.querySelector(".sidebar");
const dropdowns = document.querySelectorAll(".dropdown");

// Xử lý click vào mũi tên
dropdowns.forEach((dropdown) => {
  const arrow = dropdown.querySelector(".dropdown-arrow");
  arrow.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdown.classList.toggle("active");
  });
});

// Xử lý khi chuột rời sidebar
sidebar.addEventListener("mouseleave", () => {
  dropdowns.forEach(dropdown => {
    if (dropdown.classList.contains("active")) {
      dropdown.classList.remove("active");
    }
  });
});

// Tự động đóng menu con khi click ra ngoài
document.addEventListener("click", (e) => {
  if (!e.target.closest(".dropdown")) {
    dropdowns.forEach(dropdown => {
      dropdown.classList.remove("active");
    });
  }
});
