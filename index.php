<?php
// index.php - Website bán quần áo và mỹ phẩm (Không cần database)
session_start();

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Dữ liệu sản phẩm mẫu
$products = [
    1 => [
        'id' => 1,
        'name' => 'Áo thun nam basic',
        'description' => 'Áo thun cotton thoáng mát, chất liệu cao cấp',
        'price' => 150000,
        'category' => 'quan_ao',
        'image' => '👕',
        'stock' => 50
    ],
    2 => [
        'id' => 2,
        'name' => 'Quần jeans nữ',
        'description' => 'Quần jeans rách phong cách, form slim fit',
        'price' => 350000,
        'category' => 'quan_ao',
        'image' => '👖',
        'stock' => 30
    ],
    3 => [
        'id' => 3,
        'name' => 'Son kem lì',
        'description' => 'Son kem lì không trôi màu, bền màu 12 giờ',
        'price' => 120000,
        'category' => 'my_pham',
        'image' => '💄',
        'stock' => 100
    ],
    4 => [
        'id' => 4,
        'name' => 'Kem dưỡng ẩm',
        'description' => 'Kem dưỡng ẩm cho da khô, chiết xuất thiên nhiên',
        'price' => 250000,
        'category' => 'my_pham',
        'image' => '🧴',
        'stock' => 80
    ],
    5 => [
        'id' => 5,
        'name' => 'Áo khoác denim',
        'description' => 'Áo khoác denim unisex, phong cách streetwear',
        'price' => 450000,
        'category' => 'quan_ao',
        'image' => '🧥',
        'stock' => 25
    ],
    6 => [
        'id' => 6,
        'name' => 'Serum vitamin C',
        'description' => 'Serum sáng da chống lão hóa, thành phần tự nhiên',
        'price' => 380000,
        'category' => 'my_pham',
        'image' => '💧',
        'stock' => 60
    ],
    7 => [
        'id' => 7,
        'name' => 'Váy đầm công sở',
        'description' => 'Váy đầm thanh lịch phù hợp công sở',
        'price' => 280000,
        'category' => 'quan_ao',
        'image' => '👗',
        'stock' => 35
    ],
    8 => [
        'id' => 8,
        'name' => 'Phấn nền mineral',
        'description' => 'Phấn nền che khuyết điểm, không gây kích ứng',
        'price' => 320000,
        'category' => 'my_pham',
        'image' => '🪞',
        'stock' => 45
    ]
];

// Xử lý thêm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;
    
    if (isset($products[$product_id])) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        $message = "✅ Đã thêm '" . $products[$product_id]['name'] . "' vào giỏ hàng!";
    }
}

// Xử lý xóa khỏi giỏ hàng
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        $message = "🗑️ Đã xóa sản phẩm khỏi giỏ hàng!";
    }
}

// Xử lý cập nhật số lượng
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    $message = "🔄 Đã cập nhật giỏ hàng!";
}

// Xử lý thanh toán
if (isset($_POST['checkout'])) {
    if (!empty($_SESSION['cart'])) {
        $order_number = 'DH' . date('YmdHis');
        $total_amount = 0;
        
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            if (isset($products[$product_id])) {
                $total_amount += $products[$product_id]['price'] * $quantity;
            }
        }
        
        $success_message = "🎉 Đặt hàng thành công! Mã đơn: <strong>$order_number</strong> - Tổng tiền: <strong>" . number_format($total_amount) . "₫</strong>";
        $_SESSION['cart'] = [];
    }
}

// Lọc sản phẩm theo danh mục
$current_category = isset($_GET['category']) ? $_GET['category'] : 'all';
$filtered_products = $products;

if ($current_category !== 'all') {
    $filtered_products = array_filter($products, function($product) use ($current_category) {
        return $product['category'] === $current_category;
    });
}

// Tính tổng giỏ hàng
$cart_total = 0;
$cart_count = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    if (isset($products[$product_id])) {
        $cart_total += $products[$product_id]['price'] * $quantity;
        $cart_count += $quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion & Beauty - Shop Quần Áo & Mỹ Phẩm</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s;
        }
        
        nav ul li a:hover,
        nav ul li a.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .cart-icon {
            position: relative;
            background: #ff6b6b;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 0.8rem;
        }
        
        /* Main content */
        .main-content {
            padding: 2rem 0;
        }
        
        .hero {
            text-align: center;
            color: white;
            padding: 4rem 0;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        /* Category filter */
        .category-filter {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }
        
        .category-btn {
            padding: 0.8rem 1.5rem;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .category-btn.active,
        .category-btn:hover {
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transform: translateY(-2px);
        }
        
        /* Product grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: white;
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-category {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.9);
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .product-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .product-description {
            color: #666;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .product-price {
            font-size: 1.4rem;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        
        .add-to-cart-form {
            display: flex;
            gap: 0.5rem;
        }
        
        .quantity-input {
            width: 60px;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        
        .add-to-cart-btn {
            flex: 1;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .add-to-cart-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        /* Cart page */
        .cart-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            padding: 1.5rem 0;
            border-bottom: 1px solid #eee;
            gap: 1rem;
        }
        
        .cart-item-image {
            font-size: 3rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1rem;
            border-radius: 10px;
        }
        
        .cart-item-details {
            flex: 1;
        }
        
        .cart-item-name {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .cart-item-price {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .cart-item-quantity {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .cart-total {
            text-align: right;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 2rem 0;
            padding-top: 2rem;
            border-top: 2px solid #eee;
        }
        
        .checkout-btn {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
        }
        
        .checkout-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
        }
        
        /* Messages */
        .message {
            background: #2ecc71;
            color: white;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            text-align: center;
            animation: slideDown 0.3s ease;
        }
        
        .success-message {
            background: #27ae60;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Footer */
        footer {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            text-align: center;
            padding: 2rem 0;
            margin-top: 4rem;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }
            
            nav ul {
                gap: 1rem;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .product-grid {
                grid-template-columns: 1fr;
            }
            
            .cart-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Fashion & Beauty</div>
                <nav>
                    <ul>
                        <li><a href="index.php" class="<?php echo (!isset($_GET['page']) || $_GET['page'] === 'home') ? 'active' : ''; ?>">Trang chủ</a></li>
                        <li><a href="index.php?category=quan_ao" class="<?php echo $current_category === 'quan_ao' ? 'active' : ''; ?>">Quần áo</a></li>
                        <li><a href="index.php?category=my_pham" class="<?php echo $current_category === 'my_pham' ? 'active' : ''; ?>">Mỹ phẩm</a></li>
                        <li><a href="index.php?page=cart" class="cart-icon">
                            Giỏ hàng 
                            <?php if ($cart_count > 0): ?>
                                <span class="cart-count"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main-content">
            <?php if (isset($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($success_message)): ?>
                <div class="message success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if (!isset($_GET['page']) || $_GET['page'] === 'home'): ?>
                <!-- Trang chủ -->
                <div class="hero">
                    <h1>Chào mừng đến với Fashion & Beauty</h1>
                    <p>Khám phá bộ sưu tập quần áo và mỹ phẩm mới nhất</p>
                </div>
            <?php endif; ?>

            <?php if ($_GET['page'] === 'cart'): ?>
                <!-- Trang giỏ hàng -->
                <h2 style="color: white; text-align: center; margin-bottom: 2rem;">Giỏ Hàng Của Bạn</h2>
                
                <div class="cart-container">
                    <?php if (empty($_SESSION['cart'])): ?>
                        <p style="text-align: center; padding: 2rem;">Giỏ hàng của bạn đang trống</p>
                    <?php else: ?>
                        <form method="POST">
                            <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>
                                <?php if (isset($products[$product_id])): ?>
                                    <?php $product = $products[$product_id]; ?>
                                    <div class="cart-item">
                                        <div class="cart-item-image"><?php echo $product['image']; ?></div>
                                        <div class="cart-item-details">
                                            <div class="cart-item-name"><?php echo $product['name']; ?></div>
                                            <div class="cart-item-price"><?php echo number_format($product['price']); ?>₫</div>
                                        </div>
                                        <div class="cart-item-quantity">
                                            <input type="number" name="quantity[<?php echo $product_id; ?>]" 
                                                   value="<?php echo $quantity; ?>" min="1" 
                                                   class="quantity-input">
                                            <a href="index.php?page=cart&remove_from_cart=<?php echo $product_id; ?>" 
                                               class="remove-btn" 
                                               style="background: #e74c3c; color: white; padding: 0.5rem 1rem; border-radius: 5px; text-decoration: none;">
                                                Xóa
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            
                            <div class="cart-total">
                                Tổng cộng: <?php echo number_format($cart_total); ?>₫
                            </div>
                            
                            <button type="submit" name="update_cart" class="checkout-btn" style="background: #3498db; margin-bottom: 1rem;">
                                Cập nhật giỏ hàng
                            </button>
                            
                            <button type="submit" name="checkout" class="checkout-btn">
                                Thanh toán
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Trang sản phẩm -->
                <div class="category-filter">
                    <a href="index.php" class="category-btn <?php echo $current_category === 'all' ? 'active' : ''; ?>">
                        Tất cả sản phẩm
                    </a>
                    <a href="index.php?category=quan_ao" class="category-btn <?php echo $current_category === 'quan_ao' ? 'active' : ''; ?>">
                        Quần áo
                    </a>
                    <a href="index.php?category=my_pham" class="category-btn <?php echo $current_category === 'my_pham' ? 'active' : ''; ?>">
                        Mỹ phẩm
                    </a>
                </div>

                <div class="product-grid">
                    <?php foreach ($filtered_products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php echo $product['image']; ?>
                            </div>
                            <div class="product-category">
                                <?php echo $product['category'] === 'quan_ao' ? '👕 Quần áo' : '💄 Mỹ phẩm'; ?>
                            </div>
                            <div class="product-info">
                                <div class="product-name"><?php echo $product['name']; ?></div>
                                <div class="product-description"><?php echo $product['description']; ?></div>
                                <div class="product-price"><?php echo number_format($product['price']); ?>₫</div>
                                <form method="POST" class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="quantity-input">
                                    <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                                        Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 Fashion & Beauty. All rights reserved.</p>
            <p>Hotline: 0900 123 456 - Email: contact@fashionbeauty.com</p>
        </div>
    </footer>
</body>
</html>
