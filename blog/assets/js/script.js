// const isLocal = location.hostname === "127.0.0.1" || location.hostname === "localhost";
// document.write(`<base href="${isLocal ? "/blog/" : "/PersonalBlogWebsite/blog/"}">`);

//Đặt title cho trang
document.title = "Blog của Thân";
//Đặt favicon
const link = document.createElement("link");
link.rel = "favicon/icon";
link.href = "assets/icons/icons8-blog-100.ico";
document.head.appendChild(link);

const basePath =
  location.hostname === "127.0.0.1" || location.hostname === "localhost"
    ? "/blog/"
    : "/PersonalBlogWebsite/blog/";
$("#toggle-btn").load(`${basePath}components/ruler.html`, function () {
  let isBorderOn = false;

  $("#toggle-btn").on("click", function () {
    isBorderOn = !isBorderOn;
    $("body").toggleClass("border", isBorderOn);
  });
});

//Cuộn trang thật smooth vì có sử dụng thẻ base nên phải script
$(document).on("click", ".scroll-link", function (e) {
  e.preventDefault();

  const targetId = this.hash.slice(1);
  const target = document.getElementById(targetId);

  if (target) {
    const offset = $(target).offset().top;

    $("html, body").animate(
      {
        scrollTop: offset,
      },
      600
    ); // thời gian scroll: 600ms

    // Cập nhật URL nếu muốn
    history.pushState(null, null, this.hash);
  }
});

// Load navbar
$("#Navbar").load(`${basePath}components/navbar.html`);
// Load footer
$("#Footer").load(`${basePath}components/footer.html`);
// Load feedbaccck
$("#Feedback").load(`${basePath}components/feedback.html`);








//
// Loading Screen
//

const minimumLoadingTime = 1500; // Ít nhất 500ms mới quyết định có hiện loading
let loadingScreenDiv = null; // Ban đầu chưa tạo
let loadingContentDiv = null;
let loadingShown = false;

// Chuẩn bị sẵn loading content (nhưng chưa gắn)
function createLoadingScreen() {
  loadingScreenDiv = document.createElement("div");
  loadingScreenDiv.id = "loading-screen";

  loadingContentDiv = document.createElement("div");
  loadingContentDiv.className = "loading-content";

  const logoImage = document.createElement("img");
  logoImage.className = "loading-graphic"; // gấp đôi chữ d
  logoImage.src = `${basePath}assets/images/load/loading-graphic.gif`;
  logoImage.alt = "Logo";
  loadingContentDiv.appendChild(logoImage);

  const loadingTitle = document.createElement("h1");
  loadingTitle.className = "loading-title";
  loadingTitle.innerText = "Chào mừng bạn đến với Blog";
  loadingContentDiv.appendChild(loadingTitle);

  const loadingSubTitle = document.createElement("p");
  loadingSubTitle.className = "loading-sub-title";
  loadingSubTitle.innerText = "Chúng tôi đang chuẩn bị nội dung cho bạn!";
  loadingContentDiv.appendChild(loadingSubTitle);

  loadingScreenDiv.appendChild(loadingContentDiv);
}

// Tạo trước
createLoadingScreen();

// Sau 500ms mới quyết định có cần show loading không
const showLoadingTimer = setTimeout(() => {
  if (!loadingShown) {
    document.body.appendChild(loadingScreenDiv);
    document.body.style.overflow = 'hidden';
    loadingShown = true;
  }
}, minimumLoadingTime);

//
// Xử lý sau khi tài nguyên tải xong
//
window.onload = function () {
  clearTimeout(showLoadingTimer); // Clear timeout nếu load nhanh

  if (loadingShown) {
    // Nếu đã show loading, bắt đầu fade-out sau 3s
    setTimeout(() => {
      loadingContentDiv.classList.add('fade-out');

      setTimeout(() => {
        if (loadingScreenDiv && loadingScreenDiv.parentNode) {
          document.body.removeChild(loadingScreenDiv);
        }
        const mainContent = document.getElementById("mainContent");
        if (mainContent) {
          mainContent.style.display = "block";
        }
        document.body.style.overflow = 'auto';
      }, 800); // Fade out xong 800ms
    }, 3000); // Giữ màn loading 3 giây rồi mới fade
  } else {
    // Nếu chưa show loading (vì tải nhanh), chỉ cần hiện nội dung chính
    const mainContent = document.getElementById("mainContent");
    if (mainContent) {
      mainContent.style.display = "block";
    }
    document.body.style.overflow = 'auto';
  }
};
