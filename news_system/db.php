<?php
// db.php

$host = 'localhost';
$db_name = 'news_system'; // اسم قاعدة البيانات التي أنشأتها
$username = 'root'; // اسم مستخدم قاعدة البيانات (الافتراضي في XAMPP)
$password = ''; // كلمة المرور (الافتراضية فارغة في XAMPP)

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// بدء الجلسة (Session) لاستخدامها في تسجيل الدخول
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
