let lastScrollTop = 0; // Vị trí cuộn trước đó
const navbar = document.getElementById("Navbar"); // Lấy navbar

// Sự kiện cuộn
window.addEventListener("scroll", () => {
  let currentScroll = window.pageYOffset || document.documentElement.scrollTop; // Vị trí cuộn hiện tại
  
  // Kiểm tra hướng cuộn
  if (currentScroll > lastScrollTop) {
    // Cuộn xuống -> Ẩn navbar
    navbar.classList.remove("show-navbar"); // Ẩn navbar khi cuộn xuống
    navbar.classList.add("hide-navbar"); // Thêm lớp hide-navbar
  } else {
    // Cuộn lên -> Hiển thị navbar
    navbar.classList.remove("hide-navbar"); // Loại bỏ lớp hide-navbar
    navbar.classList.add("show-navbar"); // Thêm lớp show-navbar
  }
  
  // Cập nhật vị trí cuộn
  lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Đảm bảo không có giá trị âm
});


const showMenu = (toggleId, navbarId) => {
  const toggle = document.querySelector(toggleId),
    nav = document.querySelector(navbarId);
  toggle.addEventListener("click", () => {
    console.log("");
    nav.classList.toggle("show-menu");
    toggle.classList.toggle("show-icon");
    // KHÓA hoặc MỞ cuộn body
    if (nav.classList.contains("show-menu")) {
      document.body.style.overflow = "hidden"; // Không cuộn được
    } else {
      document.body.style.overflow = ""; // Trả lại bình thường
    }
  });
};
showMenu(".navbar-btn", ".navbar-menu");

const dropdowns = document.querySelectorAll(".dropdown");

dropdowns.forEach((dropdown) => {
  const arrow = dropdown.querySelector(".dropdown-arrow");

  arrow.addEventListener("click", (e) => {
    e.stopPropagation(); // Ngăn chặn click lan ra ngoài
    dropdown.classList.toggle("active"); // Toggle mở/đóng
  });
});
