<?php
// deleted_news.php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// جلب الأخبار المحذوفة
$stmt = $conn->query("SELECT news.*, categories.name AS category_name FROM news 
                       JOIN categories ON news.category_id = categories.id 
                       WHERE news.status = 'deleted' ORDER BY news.created_at DESC");
$deleted_news = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الأخبار المحذوفة</title>
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
        <h2>الأخبار المحذوفة</h2>
        <table>
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>الفئة</th>
                    <th>تاريخ الحذف (النشر)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($deleted_news)): ?>
                    <tr>
                        <td colspan="3">لا توجد أخبار محذوفة.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($deleted_news as $news): ?>
                    <tr>
                        <td><?= htmlspecialchars($news['title']); ?></td>
                        <td><?= htmlspecialchars($news['category_name']); ?></td>
                        <td><?= date('Y-m-d', strtotime($news['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
