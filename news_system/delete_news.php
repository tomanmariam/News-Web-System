<?php
// delete_news.php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $news_id = $_GET['id'];
    
    // تغيير حالة الخبر إلى 'deleted' بدلاً من حذفه فعلياً
    $stmt = $conn->prepare("UPDATE news SET status = 'deleted' WHERE id = ?");
    $stmt->execute([$news_id]);
}

// إعادة التوجيه إلى لوحة التحكم
header("Location: dashboard.php");
exit();
?>
