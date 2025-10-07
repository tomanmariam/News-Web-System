<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// view_categories.php
require 'db.php';

// التحقق من أن المستخدم مسجل دخوله
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// جلب جميع الفئات
$stmt = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عرض الفئات</title>
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
        <h2>جميع الفئات</h2>
        <table>
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>اسم الفئة</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= $category['id']; ?></td>
                    <td><?= htmlspecialchars($category['name']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
