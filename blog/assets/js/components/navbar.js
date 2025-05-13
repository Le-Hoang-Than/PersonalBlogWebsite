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
$(document).ready(function () {
    $.ajax({
        url: '/PersonalBlogWebsite/blog/php/api/get_navbar_categories.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            const container = $('#navbar-dynamic-menu');
            container.empty();

            data.forEach(cat => {
                if (cat.children && cat.children.length > 0) {
                    // Nếu có subcategory
                    let submenu = '<ul class="navbar-submenu">';
                    cat.children.forEach(sub => {
                        submenu += `<li><a class="navbar-link" href="/PersonalBlogWebsite/blog/${cat.slug}/${sub.slug}/"><span>${sub.name}</span></a></li>`;
                    });
                    submenu += '</ul>';

                    container.append(`
                        <li class="dropdown">
                            <div class="navbar-link">
                                <a href="/PersonalBlogWebsite/blog/${cat.slug}/"><span>${cat.name}</span></a>
                                <img class="icon dropdown-arrow" src="/PersonalBlogWebsite/blog/assets/images/navbar-images/icons8-arrow-50.png" alt="menu">
                            </div>
                            ${submenu}
                        </li>
                    `);
                } else {
                    // Nếu không có subcategory
                    container.append(`
                        <li><a class="navbar-link" href="/PersonalBlogWebsite/blog/${cat.slug}/"><span>${cat.name}</span></a></li>
                    `);
                }
            });
        },
        error: function (err) {
            console.error("Không thể tải navbar:", err);
        }
    });
});
