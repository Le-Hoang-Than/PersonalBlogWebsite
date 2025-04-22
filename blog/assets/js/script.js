const isLocal =
  location.hostname === "127.0.0.1" || location.hostname === "localhost";
const base = document.createElement("base");
base.href = isLocal ? "." : "/PersonalBlogWebsite";
document.head.appendChild(base);
// const basePath =
//   location.hostname === "127.0.0.1" ? "" : "/PersonalBlogWebsite";
// // Load navbar
// $("#Navbar").load(`${basePath}/blog/components/navbar.html`);

// // Load ruler
// $("#toggle-btn").load(`${basePath}/blog/components/ruler.html`, function () {
//   let isBorderOn = false;

//   $("#toggle-btn").on("click", function () {
//     isBorderOn = !isBorderOn;
//     $("body").toggleClass("border", isBorderOn);
//   });
// });
