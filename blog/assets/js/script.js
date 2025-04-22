// Xác định base path: dùng cho cả localhost và GitHub Pages
const basePath = location.hostname === "localhost" ? "" : "/PersonalBlogWebsite";

// Load Navbar
$("#Navbar").load(`${basePath}/blog/components/navbar.html`);

// Load Ruler và gán sự kiện toggle
$("#toggle-btn").load(`${basePath}/blog/components/ruler.html`, function () {
  let isBorderOn = false;

  $("#toggle-btn").on("click", function () {
    isBorderOn = !isBorderOn;
    $("body").toggleClass("border", isBorderOn);
  });
});
