<?php
// login.php
require 'db.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // تم التحقق بنجاح
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: dashboard.php"); // توجيه إلى لوحة التحكم
        exit();
    } else {
        $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>تسجيل الدخول</h2>
        <?php if(!empty($error)): ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="email">البريد الإلكتروني:</label>
            <input type="email" name="email" required>

            <label for="password">كلمة المرور:</label>
            <input type="password" name="password" required>

            <button type="submit">تسجيل الدخول</button>
        </form>
        <p>ليس لديك حساب؟ <a href="register.php">أنشئ حساباً جديداً</a></p>
    </div>
</body>
</html>
