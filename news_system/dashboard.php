<?php
// dashboard.php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->query("SELECT news.*, categories.name AS category_name FROM news 
                       JOIN categories ON news.category_id = categories.id 
                       WHERE news.status = 'published' ORDER BY news.created_at DESC");
$news_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>لوحة التحكم</h1>
        <p>مرحباً بك، <?= htmlspecialchars($_SESSION['user_name']); ?></p>
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
        <h2>جميع الأخبار</h2>
        <table>
            <thead>
                <tr>
                    <!-- تم حذف عمود الصورة من هنا -->
                    <th>العنوان</th>
                    <th>الفئة</th>
                    <th>تاريخ النشر</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news_list as $news): ?>
                <tr>
                    <!-- تم دمج الصورة والعنوان في خلية واحدة -->
                    <td>
                        <div class="news-title-container">
                            <?php if (!empty($news['image'])): ?>
                                <img src="<?= htmlspecialchars($news['image']); ?>" alt="صورة الخبر" class="news-thumbnail">
                            <?php endif; ?>
                            <span><?= htmlspecialchars($news['title']); ?></span>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($news['category_name']); ?></td>
                    <td><?= date('Y-m-d', strtotime($news['created_at'])); ?></td>
                    <td>
                        <a href="edit_news.php?id=<?= $news['id']; ?>">تعديل</a>
                        <a href="delete_news.php?id=<?= $news['id']; ?>" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا الخبر؟');">حذف</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
