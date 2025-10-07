<?php
// edit_news.php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$news_id = $_GET['id'] ?? null;
if (!$news_id) {
    header("Location: dashboard.php");
    exit();
}

// جلب بيانات الخبر الحالي
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$news_id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    echo "الخبر غير موجود.";
    exit();
}

// جلب الفئات
$categories_stmt = $conn->query("SELECT * FROM categories");
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    
    // تحديث البيانات
    $stmt = $conn->prepare("UPDATE news SET title = ?, content = ?, category_id = ? WHERE id = ?");
    if ($stmt->execute([$title, $content, $category_id, $news_id])) {
        $message = "تم تحديث الخبر بنجاح!";
        // إعادة جلب البيانات المحدثة لعرضها في النموذج
        $stmt->execute([$news_id]);
        $news = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "حدث خطأ أثناء التحديث.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل الخبر</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>لوحة التحكم</h1>
        <nav>
            <a href="dashboard.php">العودة إلى لوحة التحكم</a>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>تعديل الخبر</h2>
            <?php if(!empty($message)): ?>
                <p class="message"><?= $message; ?></p>
            <?php endif; ?>
            <form action="edit_news.php?id=<?= $news_id; ?>" method="post">
                <label for="title">عنوان الخبر:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($news['title']); ?>" required>

                <label for="content">تفاصيل الخبر:</label>
                <textarea name="content" rows="10" required><?= htmlspecialchars($news['content']); ?></textarea>

                <label for="category_id">الفئة:</label>
                <select name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id']; ?>" <?= ($news['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">تحديث الخبر</button>
            </form>
        </div>
    </main>
</body>
</html>
