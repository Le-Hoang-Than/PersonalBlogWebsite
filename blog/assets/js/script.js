async function loadruler() {
  try {
    const response = await fetch("/blog/components/ruler.html");
    const rulerHtml = await response.text();
    document.querySelector("#toggle-btn").innerHTML = rulerHtml;
  } catch (error) {
    console.error("Lỗi khi tải navbar:", error);
    document.querySelector("#toggle-btn").innerHTML =
      "<p>Không thể tải ruler.</p>";
  }
}
loadruler();
const toggleBtn = document.getElementById("toggle-btn");
let isBorderOn = false;

toggleBtn.addEventListener("click", function () {
    isBorderOn = !isBorderOn;

    if (isBorderOn) {
        document.body.classList.add("border");
    } else {
        document.body.classList.remove("border");
    }
});

async function loadNavbar() {
  try {
    const response = await fetch("/blog/components/navbar.html");
    const navbarHtml = await response.text();
    document.querySelector("#Navbar").innerHTML = navbarHtml;
  } catch (error) {
    console.error("Lỗi khi tải navbar:", error);
    document.querySelector("#Navbar").innerHTML =
      "<p>Không thể tải navbar.</p>";
  }
}
loadNavbar(); // Gọi hàm để tải navbar khi trang tải xong
