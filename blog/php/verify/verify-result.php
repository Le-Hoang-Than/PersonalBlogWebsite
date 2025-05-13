<!-- blog/php/verify/verify-result.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Xác minh' ?></title>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
    }

    .verify-container {
      height: 100vh;
      width: 100vw;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    img{
      height: 20rem;
      width: 20rem;
    }
  </style>
</head>

<body>
  <div class="verify-container">
    <h1><?= $title ?? 'Xác minh' ?></h1>
    <p><?= $message ?? 'Không có nội dung hiển thị.' ?></p>
    <img src="/PersonalBlogWebsite/blog/assets/images/verify/<?=$image?>" alt="Trạng thái xác minh">
    <!-- <a href="../../index.html">Quay lại trang chủ</a> -->
  </div>
</body>

</html>