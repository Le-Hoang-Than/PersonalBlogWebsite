let lastScrollTop = 0;
$(window).on('scroll', function() {
//   if (window.innerWidth > 767) return; // Không làm gì nếu không phải mobile

  const st = $(this).scrollTop();

  if (st > lastScrollTop && st > 50) {
    $('#Navbar').addClass('hide');
  } else {
    $('#Navbar').removeClass('hide');
  }
  lastScrollTop = st;
});

