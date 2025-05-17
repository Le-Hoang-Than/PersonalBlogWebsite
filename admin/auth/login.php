<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin</title>
    <link rel="stylesheet" href="/PersonalBlogWebsite/blog/assets/css/main.css">
    <link rel="icon" type="image/x-icon" href="/PersonalBlogWebsite/admin/dashboard/assets/images/icons8-admin-100.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 2rem 3rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .login-container h2 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            text-align: center;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
        }

        .login-container label {
            margin-bottom: 0.5rem;
        }

        .login-container input {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .login-container button {
            background-color: black;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #353535;
        }

        .error {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>Đăng nhập Admin</h2>


        <form method="POST" action="send-token.php">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Email..." required>

            <label for="password">Mật khẩu</label>
            <input type="password" name="password" placeholder="Mật khẩu..." required>
            <?php if (!empty($_GET['error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <button type="submit">Gửi mã xác nhận</button>
        </form>
        <hr>
        <form action="teacher.php">
            <small>Trang quản trị thao tác trực tiếp với các bài viết trên trang web, 
                <b style="color:red;">nếu không phải GIẢNG VIÊN vui lòng không thao tác các chức năng bên trong.</b>
            </small>
            <br>
            <button type="submit">Giảng viên đăng nhập</button>

        </form>
    </div>

</body>
<script>
    const errorBox = document.querySelector(".error");
    if (errorBox) {
        document.querySelectorAll("input").forEach(input => {
            input.addEventListener("focus", () => {
                errorBox.textContent = '';
            });
        });
    }
</script>

</html>