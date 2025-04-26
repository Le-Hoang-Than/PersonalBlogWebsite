// Ẩn Navbar khi scroll xuống, hiện lại khi scroll lên
let lastScrollTop = 0;
window.addEventListener("scroll", function () {
  const st = window.scrollY || document.documentElement.scrollTop;

  if (st > lastScrollTop && st > 50) {
    document.getElementById("Navbar").classList.add("hide");
  } else {
    document.getElementById("Navbar").classList.remove("hide");
    menu.classList.remove("open"); 
  }
  lastScrollTop = st;
});

// Toggle menu khi click menu-btn
const menu = document.querySelector(".navbar-menu");
const menuBtn = document.querySelector(".menu-btn");
menuBtn.addEventListener("click", () => {
  const isOpen = menu.classList.toggle("open");

  if (!isOpen) {
    // Nếu đang đóng menu thì reset toàn bộ dropdowns
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
    e.stopPropagation(); // Ngừng sự kiện lan ra ngoài (ngăn ngừa việc đóng menu khi click vào dropdown)

    const parent = this.closest(".drop-down");
    const submenu = parent.querySelector(".navbar-sub-menu");
    const icon = this;
    const dropdownSpan = parent.querySelector(".item > a > span");

    if (parent.classList.contains("active")) {
      // Đang mở => thu gọn
      submenu.style.display = "none"; // Đóng submenu

      dropdownSpan.style.backgroundSize = "0% 2px"; // Reset hover style khi đóng

      parent.classList.remove("active"); // Loại bỏ class active
      icon.style.transform = "rotate(0deg)"; // Đổi icon về trạng thái đóng
    } else {
      // Đang đóng => bung ra
      submenu.style.display = "block"; // Mở submenu

      parent.classList.add("active"); // Thêm class active
      dropdownSpan.style.backgroundSize = ""; // Cho hover span hoạt động lại bình thường
      icon.style.transform = "rotate(180deg)"; // Đổi icon sang trạng thái mở
    }
  });
});
