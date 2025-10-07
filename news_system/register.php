<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// register.php
require 'db.php';

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // تشفير كلمة المرور

    try {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $password])) {
            $message = "تم إنشاء الحساب بنجاح! يمكنك الآن <a href='login.php'>تسجيل الدخول</a>.";
        }
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) { // خطأ الإدخال المكرر
            $message = "هذا البريد الإلكتروني مسجل بالفعل.";
        } else {
            $message = "حدث خطأ ما. يرجى المحاولة مرة أخرى.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب جديد</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>إنشاء حساب جديد</h2>
        <?php if(!empty($message)): ?>
            <p class="message"><?= $message; ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <label for="name">الاسم:</label>
            <input type="text" name="name" required>

            <label for="email">البريد الإلكتروني:</label>
            <input type="email" name="email" required>

            <label for="password">كلمة المرور:</label>
            <input type="password" name="password" required>

            <button type="submit">إنشاء حساب</button>
        </form>
        <p>لديك حساب بالفعل؟ <a href="login.php">سجل الدخول</a></p>
    </div>
</body>
</html>
