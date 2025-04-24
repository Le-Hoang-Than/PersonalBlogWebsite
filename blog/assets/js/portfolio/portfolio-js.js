





window.addEventListener('scroll', () => {
  const sections = document.querySelectorAll("section");
  const navLinks = document.querySelectorAll(".navbar-bottom-content a");

  let current = "";

  sections.forEach(section => {
    const sectionTop = section.offsetTop - 50;
    if (pageYOffset >= sectionTop) {
      current = section.getAttribute("id");
    }
  });

  navLinks.forEach(link => {
    const icon = link.querySelector(".icon");

    if (icon) {
      icon.classList.remove("active");

      if (link.getAttribute("href") === `portfolio/#${current}`) {
        icon.classList.add("active");
      }
    }
  });
});













function showVideo(videoSrc) {
  const popup = document.getElementById("videoPopup");
  const video = document.getElementById("popupVideo");
  const source = document.getElementById("popupSource");

  source.src = videoSrc;
  video.load();
  popup.style.display = "flex";
  document.body.style.overflow = "hidden";
}

function closePopup() {
  const popup = document.getElementById("videoPopup");
  const video = document.getElementById("popupVideo");

  video.pause();
  video.currentTime = 0;
  popup.style.display = "none";
  document.body.style.overflow = "";
}









// Gán sự kiện cho tất cả các nút
document.querySelectorAll(".detail-btn").forEach(function(button) {
  button.addEventListener("click", function () {
    // Tìm đúng phần tử cha gần nhất có chứa .detail-content (ví dụ thẻ .project)
    const parent = this.closest(".project"); 
    const detail = parent.querySelector(".detail-content");
    const isHidden = window.getComputedStyle(detail).display === "none";

    detail.style.display = isHidden ? "flex" : "none";
    this.textContent = isHidden ? "Ẩn bớt" : "Xem chi tiết";
  });
});
