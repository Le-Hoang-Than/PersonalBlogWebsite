// Load navbar
$("#Navbar").load("/blog/components/navbar.html");

// Load ruler
$("#toggle-btn").load("/blog/components/ruler.html", function () {
  let isBorderOn = false;

  $("#toggle-btn").on("click", function () {
    isBorderOn = !isBorderOn;
    $("body").toggleClass("border", isBorderOn);
  });
});
