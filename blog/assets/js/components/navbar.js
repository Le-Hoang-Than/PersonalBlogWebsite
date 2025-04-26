let lastScrollTop = 0;
$(window).on("scroll", function () {
  //   if (window.innerWidth > 767) return; // Không làm gì nếu không phải mobile

  const st = $(this).scrollTop();

  if (st > lastScrollTop && st > 50) {
    $("#Navbar").addClass("hide");
  } else {
    $("#Navbar").removeClass("hide");
  }
  lastScrollTop = st;
});

// menu
const menu = document.querySelector(".navbar-menu");
const menuBtn = document.querySelector(".menu-btn");

menuBtn.addEventListener("click", () => {
  menu.classList.toggle("open");
  menu.style.transition = "transform 0.5s ease";
});

menu.addEventListener("transitioned", function () {
  this.removeAttribute("style");
});

menu.querySelectorAll(".drop-down .icon").forEach((arrow) => {
  arrow.addEventListener("click", function () {
    this.closest(".drop-down").classList.toggle("active");
  });
});

