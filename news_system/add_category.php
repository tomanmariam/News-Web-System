<?php
// add_category.php
require 'db.php';

// التحقق من أن المستخدم مسجل دخوله
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['name'])) {
    $name = $_POST['name'];

    try {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        if ($stmt->execute([$name])) {
            $message = "تمت إضافة الفئة بنجاح!";
        }
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            $message = "هذه الفئة موجودة بالفعل.";
        } else {
            $message = "حدث خطأ ما.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة فئة جديدة</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>لوحة التحكم</h1>
        <nav>
            <a href="dashboard.php">عرض الأخبار</a>
            <a href="add_news.php">إضافة خبر</a>
            <a href="view_categories.php">عرض الفئات</a>
            <a href="add_category.php">إضافة فئة</a>
            <a href="deleted_news.php">الأخبار المحذوفة</a>
            <a href="logout.php">تسجيل الخروج</a>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>إضافة فئة جديدة</h2>
            <?php if(!empty($message)): ?>
                <p class="message"><?= $message; ?></p>
            <?php endif; ?>
            <form action="add_category.php" method="post">
                <label for="name">اسم الفئة:</label>
                <input type="text" name="name" required>
                <button type="submit">إضافة</button>
            </form>
        </div>
    </main>
</body>
</html>
