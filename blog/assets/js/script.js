$("#toggle-btn").load(`/PersonalBlogWebsite/blog/components/ruler.html`, function () {
  let isBorderOn = false;

  $("#toggle-btn").on("click", function () {
    isBorderOn = !isBorderOn;
    $("body").toggleClass("border", isBorderOn);
  });
});


//Load page-updating
$("#Page-updating").load(`/PersonalBlogWebsite/blog/components/updating-page.html`);
// Load navbar
$("#Navbar").load(`/PersonalBlogWebsite/blog/components/navbar.html`);
// Load footer
$("#Footer").load(`/PersonalBlogWebsite/blog/components/footer.html`);
// Load feedbaccck
$("#Feedback").load(`/PersonalBlogWebsite/blog/components/feedback.html`);
//Load home page
$("#Home").load(`/PersonalBlogWebsite/blog/components/home.html`);








//
// Loading Screen
//
const minimumLoadingTime = 800; // Ít nhất 800ms mới quyết định có hiện loading
let loadingScreenDiv = null; // Ban đầu chưa tạo
let loadingContentDiv = null;
let loadingShown = false;

// Chuẩn bị sẵn loading content (nhưng chưa gắn)
function createLoadingScreen() {
  loadingScreenDiv = document.createElement("div");
  loadingScreenDiv.id = "loading-screen";

  loadingContentDiv = document.createElement("div");
  loadingContentDiv.className = "loading-content";

  const loadingTitle = document.createElement("h1");
  loadingTitle.className = "loading-title";
  loadingTitle.innerText = "Chào mừng bạn đến với Blog";
  loadingContentDiv.appendChild(loadingTitle);

  const loadingSubTitle = document.createElement("p");
  loadingSubTitle.className = "loading-sub-title";
  loadingSubTitle.innerText = "Chúng tôi đang chuẩn bị nội dung cho bạn!";
  loadingContentDiv.appendChild(loadingSubTitle);

  const logoImage = document.createElement("img");
  logoImage.className = "loading-graphic"; // gấp đôi chữ d
  logoImage.src = `/PersonalBlogWebsite/blog/assets/images/load/loading-graphic.png`;
  logoImage.alt = "Logo";
  loadingContentDiv.appendChild(logoImage);

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
