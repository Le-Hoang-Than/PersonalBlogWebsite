// Load navbar
$("#Navbar").load("./components/navbar.html");

// Load ruler
$("#toggle-btn").load("./components/ruler.html", function () {
  let isBorderOn = false;

  $("#toggle-btn").on("click", function () {
    isBorderOn = !isBorderOn;
    $("body").toggleClass("border", isBorderOn);
  });
});
