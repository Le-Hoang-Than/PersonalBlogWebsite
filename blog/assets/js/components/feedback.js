// Validation logic
function validateEmail() {
  const emailField = document.getElementById("email-field");
  const emailError = document.querySelector(".error-message");

  // Regex đã sửa
  const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;

  if (!emailRegex.test(emailField.value)) {
    emailError.textContent =
      "Vui lòng nhập email hợp lệ (ví dụ: example@gmail.com)";
    return false;
  }

  emailError.textContent = ""; // Sử dụng textContent thay vì innerHTML
  return true;
}

//Submit
document.querySelector("form").addEventListener("submit", function (e) {
  if (!validateEmail()) {
    e.preventDefault(); // Ngăn form submit nếu validation fail
    document.getElementById("email-field").focus();
  } else {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    fetch("/PersonalBlogWebsite/blog/php/feedback/store-temp-feedback.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((result) => {
        alert(
          "Phản hồi của bạn đã được ghi nhận. Vui lòng kiểm tra email để xác thực.\n"+
          "Nếu không thấy email trong hộp thư đến, vui lòng kiểm tra thư mục Spam/Thư rác."
        );
        form.reset();
      })
      .catch((error) => {
        console.error("Lỗi:", error);
        alert("Gửi phản hồi thất bại. Vui lòng thử lại sau.");
      });
  }
});
