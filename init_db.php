<?php
// تأكد من تشغيل هذا الملف مرة واحدة لإنشاء قاعدة البيانات
require_once 'config.php';

$sql = "
-- جدول المسؤولين (يمكنك إضافة أكثر من سجل أو استخدام بيانات ثابتة)
CREATE TABLE IF NOT EXISTS admin (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT
);

-- جدول المعلمين
CREATE TABLE IF NOT EXISTS teachers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    phone TEXT,
    class TEXT
);

-- جدول الطلاب
CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    phone TEXT UNIQUE,
    teacher_id INTEGER,
    points INTEGER DEFAULT 0,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id)
);

-- جدول المنتجات
CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    description TEXT,
    image TEXT,
    price INTEGER,         -- السعر بالنقاط
    stock INTEGER,         -- المخزون
    is_visible INTEGER DEFAULT 1  -- 1: ظاهر، 0: مخفي
);

-- جدول الطلبات
CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_phone TEXT,
    product_id INTEGER,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status TEXT DEFAULT 'معلق',
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- جدول سجل النقاط (الإضافات والخصومات)
CREATE TABLE IF NOT EXISTS points_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER,
    points INTEGER,
    type TEXT,  -- addition or subtraction
    note TEXT,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id)
);
";

// تنفيذ الاستعلامات
$pdo->exec($sql);

// إضافة مستخدم إداري افتراضي (يمكنك تغييره لاحقاً)
$stmt = $pdo->prepare("INSERT OR IGNORE INTO admin (username, password) VALUES (:username, :password)");
$stmt->execute([
    ':username' => 'admin',
    ':password' => password_hash('admin123', PASSWORD_DEFAULT)
]);

echo "تم تهيئة قاعدة البيانات بنجاح.";
?>
