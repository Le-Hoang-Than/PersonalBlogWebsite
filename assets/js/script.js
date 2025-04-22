const basePath = location.hostname === "localhost" ? "" : "/PersonalBlogWebsite";
// Load navbar
$("#Navbar").load(`${basePath}/components/navbar.html`);

// Load ruler
$("#toggle-btn").load("./components/ruler.html", function () {
  let isBorderOn = false;

  $("#toggle-btn").on("click", function () {
    isBorderOn = !isBorderOn;
    $("body").toggleClass("border", isBorderOn);
  });
});

