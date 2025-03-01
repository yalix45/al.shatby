<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المتجر المدرسي</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/png" href="photo/icon-for-header.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Rubik:wght@300..900&display=swap");
        @import url('https://fonts.googleapis.com/css2?family=Zain:wght@200;300;400;700;800;900&display=swap');
        * {
            font-family: "Rubik", sans-serif;
            box-sizing: border-box;
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2c3e50;
            padding: 15px 30px;
            color: white;
        }
        .navbar .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            margin-left: auto;
        }
        .navbar .logo img {
            width: 50px;
            margin-left: 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .login-btn {
            display: flex;
            align-items: center;
            background: rgb(60, 154, 231);
            padding: 8px 15px;
            border-radius: 5px;
        }
        .login-btn i {
            margin-left: 5px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .products {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .product-item {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .product-item img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product-item a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            width: 100%;

        }
        .product-item a:hover {
            background: #2980b9;
        }

        /* تنسيق السعر والكمية في نفس الصف */
        .product-info {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 20px;
            align-items: center;
        }

        .product-info div {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            flex: 1;
        }

        .price {
            background-color: #f39c12;
        }

        .quantity {
            background-color: #27ae60;
        }

        .product-info p {
            font-size: 16px;
            font-weight: bold;
            color: white;
            margin: 0;
        }

        .product-info span {
            font-size: 18px;
            font-weight: bold;
            color: white;
        }

        .product-info i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .product-info img {
            width: 15px;
            vertical-align: middle;
            margin-left: 5px;
        }

        /* لتغيير العرض في الشاشات الصغيرة */
        @media (max-width: 1024px) {
            .products {
                grid-template-columns: repeat(2, 1fr); /* 2 منتجات في الصف */
            }
        }

        @media (max-width: 768px) {
            .products {
                grid-template-columns: 1fr; /* منتج واحد في الصف */
            }
            .container{
                flex-direction: column;
            }
        }
        @media (max-width: 462px) {
            .logo span{
                display: none;
            }
        }

    </style>
</head>
<body>
    <nav class="navbar">
        <a href="html/all-login.html" class="login-btn">تسجيل الدخول <i class="fas fa-user"></i></a>
        <div class="logo">
            <span>متجر الشاطبي</span>
            <img src="photo/or-icon.png" alt="Shop Icon">
        </div>
    </nav>
    
    <div class="container">
        <!-- <h2>منتجات المتجر</h2> -->
        <div class="products">
            <?php
            require_once 'config.php';
            $stmt = $pdo->query("SELECT * FROM products WHERE is_visible = 1 AND stock > 0");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($products as $prod): ?>
                <div class="product-item">
                    <h3><?= htmlspecialchars($prod['name']) ?></h3>
                    <img src="uploads/<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                    <div class="product-info">
                        <div class="price">
                            <i class="fas fa-tags" style="color: #fff;"></i>
                            <p>السعر</p>
                            <span><?= $prod['price'] ?></span>
                        </div>
                        <div class="quantity">
                            <i class="fas fa-boxes" style="color: #fff;"></i>
                            <p>الكمية</p>
                            <span><?= $prod['stock'] ?></span>
                        </div>
                    </div>
                    <a href="html/store/product.php?id=<?= $prod['id'] ?>">التفاصيل والطلب</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
