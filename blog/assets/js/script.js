const basePath =
location.hostname === "127.0.0.1" || location.hostname === "localhost" ? "/blog/" : "/PersonalBlogWebsite/blog/";

// Load ruler
$("#toggle-btn").load(`${basePath}components/ruler.html`, function () {
  let isBorderOn = false;

  $("#toggle-btn").on("click", function () {
    isBorderOn = !isBorderOn;
    $("body").toggleClass("border", isBorderOn);
  });
});

//Cuộn trang thật smooth vì có sử dụng thẻ base nên phải script
$(document).on('click', '.scroll-link', function(e) {
  e.preventDefault();

  const targetId = this.hash.slice(1);
  const target = document.getElementById(targetId);

  if (target) {
    const offset = $(target).offset().top;

    $('html, body').animate({
      scrollTop: offset
    }, 600); // thời gian scroll: 600ms

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
