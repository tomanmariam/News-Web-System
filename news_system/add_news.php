<?php
// add_news.php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// جلب الفئات لعرضها في القائمة المنسدلة
$categories_stmt = $conn->query("SELECT * FROM categories");
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $user_id = $_SESSION['user_id'];
    $image_path = '';

    // معالجة رفع الصورة
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . '_' . $image_name; // إضافة وقت لضمان اسم فريد
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            $message = "عذراً، حدث خطأ أثناء رفع الصورة.";
        }
    }

    if (empty($message)) {
        $stmt = $conn->prepare("INSERT INTO news (title, content, image, user_id, category_id) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $content, $image_path, $user_id, $category_id])) {
            $message = "تم نشر الخبر بنجاح!";
        } else {
            $message = "حدث خطأ أثناء نشر الخبر.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة خبر جديد</title>
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
            <h2>إضافة خبر جديد</h2>
            <?php if(!empty($message)): ?>
                <p class="message"><?= $message; ?></p>
            <?php endif; ?>
            <form action="add_news.php" method="post" enctype="multipart/form-data">
                <label for="title">عنوان الخبر:</label>
                <input type="text" name="title" required>

                <label for="content">تفاصيل الخبر:</label>
                <textarea name="content" rows="10" required></textarea>

                <label for="category_id">الفئة:</label>
                <select name="category_id" required>
                    <option value="">اختر فئة</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="image">صورة الخبر:</label>
                <input type="file" name="image" accept="image/*">

                <button type="submit">نشر الخبر</button>
            </form>
        </div>
    </main>
</body>
</html>
