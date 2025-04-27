
// Chọn các phần tử cần thiết
const menu = document.querySelector(".navbar-menu");
const menuBtn = document.querySelector(".menu-btn");
const overlay = document.querySelector(".overlay"); // Chọn lớp phủ

// Toggle menu khi click vào menu-btn
menuBtn.addEventListener("click", () => {
  const isOpen = menu.classList.toggle("open"); // Mở/đóng menu

  if (isOpen) {
    // Khi menu mở, lớp phủ sẽ hiển thị
    overlay.style.opacity = 1;
    overlay.style.visibility = "visible";
  } else {
    // Khi menu đóng, lớp phủ sẽ ẩn đi
    overlay.style.opacity = 0;
    overlay.style.visibility = "hidden";
  }

  if (!isOpen) {
    // Nếu menu đóng, reset toàn bộ dropdowns
    const allDropdowns = menu.querySelectorAll(".drop-down");

    allDropdowns.forEach((dropdown) => {
      dropdown.classList.remove("active");

      const submenu = dropdown.querySelector(".navbar-sub-menu");
      if (submenu) {
        submenu.style.display = "none"; // Đóng submenu
      }

      const icon = dropdown.querySelector(".icon");
      if (icon) {
        icon.style.transform = "rotate(0deg)"; // Đổi icon về trạng thái đóng
      }

      const dropdownSpan = dropdown.querySelector(".item > a > span");
      if (dropdownSpan) {
        dropdownSpan.style.backgroundSize = "0% 2px"; // Reset hover effect
      }
    });
  }
});

// Xử lý drop-down mở/đóng
menu.querySelectorAll(".drop-down .icon").forEach((arrow) => {
  arrow.addEventListener("click", function (e) {
    if (window.innerWidth > 1024) return; // Nếu kích thước màn hình là desktop thì không làm gì
    e.stopPropagation(); // Ngừng sự kiện lan ra ngoài (ngăn ngừa việc đóng menu khi click vào dropdown)

    const parent = this.closest(".drop-down"); // Lấy phần tử cha
    const submenu = parent.querySelector(".navbar-sub-menu"); // Lấy submenu
    const icon = this; // Lấy icon của dropdown
    const dropdownSpan = parent.querySelector(".item > a > span"); // Lấy span chứa hover effect

    if (parent.classList.contains("active")) {
      // Nếu dropdown đang mở => thu gọn
      submenu.style.display = "none"; // Đóng submenu
      parent.classList.remove("active"); // Loại bỏ class active
      icon.style.transform = "rotate(0deg)"; // Đổi icon về trạng thái đóng
      if (dropdownSpan) dropdownSpan.style.backgroundSize = "0% 2px"; // Reset hover style khi đóng
    } else {
      // Nếu dropdown đang đóng => bung ra
      submenu.style.display = "block"; // Mở submenu
      parent.classList.add("active"); // Thêm class active
      icon.style.transform = "rotate(180deg)"; // Đổi icon sang trạng thái mở
      if (dropdownSpan) dropdownSpan.style.backgroundSize = ""; // Cho hover span hoạt động lại bình thường
    }
  });
});
