<?php
session_start();

// Database connection
$host = 'localhost'; 
$db = 'hau_store'; 
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['flash_message'])) {
    $_SESSION['flash_message'] = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['coupon_code'])) {
        $coupon_code = $_POST['coupon_code'];
        if ($coupon_code === "ILOVEDWEB") {
            $_SESSION['coupon_applied'] = true;
            $_SESSION['flash_message'] = "Coupon applied successfully! You saved ₱800.";
        } else {
            $_SESSION['coupon_applied'] = false;
            $_SESSION['flash_message'] = "Invalid coupon code";
        }
        header('Location: cart.php');
        exit;
    } elseif (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $quantity = intval($_POST['quantity']);

        // Set the exact quantity instead of adding
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'quantity' => $quantity
        ];
        
        $_SESSION['flash_message'] = "{$product_name} quantity updated to {$quantity}!";
        
        header('Location: cart.php');
        exit;
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $product_id = $_GET['product_id'];

    if ($action === 'remove') {
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['flash_message'] = "Item removed from your cart!";
        header('Location: cart.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $session_id = session_id(); // Get the current session ID

    foreach ($_SESSION['cart'] as $product_id => $item) {
        $stmt = $pdo->prepare("INSERT INTO CartItems (SessionID, ProductID, ProductName, Quantity, Price, ImagePath) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$session_id, $product_id, $item['name'], $item['quantity'], $item['price'], $item['image']]);
    }

    $_SESSION['flash_message'] = "Checkout successful! Your items have been saved.";
    header('Location: checkout.php'); // Redirect to a confirmation page
    exit;
}

// Get flash message and reset it
$flash_message = $_SESSION['flash_message'];
$_SESSION['flash_message'] = null;

$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$discount = 200; 
$coupon_discount = (isset($_SESSION['coupon_applied']) && $_SESSION['coupon_applied']) ? 800 : 0;
$total = $subtotal - $discount - $coupon_discount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furniture Store</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f9f5f0;
        }

        /* Container Layout */
        .container {
            padding: 50px 5% 0;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 40px;
        }

        .cart-container {
            flex: 1;
            min-width: 0;
        }

        .summary-container {
            width: 350px;
            flex-shrink: 0;
        }

        /* Cart Header */
        .cart-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            color: #5d4b3e;
            margin: 0;
        }

        .item-count {
            color: #98724B;
            font-size: 15px;
        }

        /* Cart Items */
        .cart-item {
            display: flex;
            padding: 20px 0;
            border-bottom: 1px solid #D4C7B5;
            gap: 20px;
            position: relative;
        }

        .item-image {
            width: 120px;
            height: 90px;
            object-fit: cover;
            border-radius: 5px;
        }

        .item-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
    text-align: left;
    justify-content: flex-start;
    align-items: flex-start;
}


.item-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
    text-align: left; 
    justify-content: flex-start; 
    align-items: flex-start;
}

.item-info {
    width: 100%; 
}

.item-name, .item-color {
    text-align: left; 
    width: 100%; 
}

.item-info {
    width: 100%;
}

.item-name {
    font-size: 18px;
    font-weight: bold;
    color: #5d4b3e;
    margin: 0;
    text-align: left;
}

.item-color {
    font-size: 14px;
    color: #815931;
    text-align: left;
}
        .item-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Quantity Controls */
        .quantity-controls {
            display: flex;
            align-items: center;
            border: 1px solid #D4C7B5;
            border-radius: 4px;
            overflow: hidden;
            background: #f8f8f8;
            width: fit-content;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            background-color: #f4e6d4;
            border: none;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .quantity-btn:hover {
            background-color: #f0f0f0;
        }

        .quantity-input {
            width: 40px;
            height: 30px;
            text-align: center;
            border: none;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            font-size: 14px;
            outline: none;
            background: #fff;
            -moz-appearance: textfield;
            appearance: textfield;
        }

        .quantity-input::-webkit-inner-spin-button,
        .quantity-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #5c442c;
            cursor: pointer;
            font-size: 14px;
            transition: color 0.2s;
        }

        .remove-btn:hover {
            color: #44382e;
            text-decoration: underline;
        }

        .item-price {
            position: absolute;
            right: 0;
            bottom: 20px;
            font-size: 16px;
            font-weight: bold;
        }

        /* Promotion Banner */
        .promotion-banner {
            background-color: #f4e6d4;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #D4C7B5;
            color: #5c442c;
        }

        .promo-icon {
            color: #5c442c;
            font-size: 18px;
            font-weight: bold;
            background-color: #e0ccb1;
            padding: 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
        }

        /* Order Summary */
        .order-summary {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #D4C7B5;
            margin-bottom: 20px;
        }

        .summary-title {
            font-size: 20px;
            margin-bottom: 25px;
            color: #5d4b3e;
            font-weight: bold;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .summary-label {
            color: #5d4b3e;
        }

        .summary-value {
            text-align: right;
        }

        .free-text {
            color: #5d4b3e;
        }

        .total-row {
            font-size: 17px;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #D4C7B5;
        }

        /* Coupon Input */
        .coupon-input {
            display: flex;
            margin-bottom: 20px;
            position: relative;
        }

        .coupon-field {
            flex: 1;
            height: 40px;
            padding: 0 40px 0 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .coupon-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Checkout Button */
        .checkout-btn {
            width: 100%;
            padding: 15px;
            background-color: #5d4b3e;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .checkout-btn:hover {
            background-color: #463730;
        }

        /* Flash Message */
        .flash-message {
            padding: 10px 15px;
            background-color: #f4e6d4;
            color: #5c442c;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #D4C7B5;
            display: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .summary-container {
                width: 100%;
                margin-left: 0;
            }
            
            .item-price {
                position: static;
                text-align: right;
                margin-top: 10px;
            }
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Besley:ital,wght@0,400..900;1,400..900&family=DM+Serif+Text:ital@0;1&family=Montaga&family=Volkhov:ital,wght@0,400;0,700;1,400;1,700&family=Yeseva+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Besley:ital,wght@0,400..900;1,400..900&family=DM+Serif+Text:ital@0;1&family=Montaga&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Volkhov:ital,wght@0,400;0,700;1,400;1,700&family=Yeseva+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="header.css">

</head>
<body>

<header>
    <div class="nav-bar">
        <a href="home.php" class="logo-link">
            <div class="logo windsong-logo">Splatter</div>
        </a>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="blog.php">Blog</a></li>
            </ul>
        </nav>
        <div class="icons">
            <a href="cart.php" class="icon-link">
                <i class="fas fa-shopping-cart"></i>
            </a>
            <a href="login.php" class="icon-link">
                <i class="fas fa-user"></i>
            </a>
        </div>
    </div>
</header>
<section class="banner">
    <div class="banner-overlay"></div> 
    <div class="banner-content">
        <h1>Cart</h1>
        <div class="breadcrumb">
            <a href="home.php">Home</a> > Cart
        </div>
    </div>
</section>

<div class="container">
    <div class="cart-container">
        <div class="cart-header">
            <h1>Cart</h1>
            <div class="item-count"><?php echo count($_SESSION['cart']); ?> ITEMS</div>
        </div>
        
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                <div class="cart-item" data-id="<?php echo $product_id; ?>" data-price="<?php echo $item['price']; ?>">
                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="item-image">
                    <div class="item-details">
                        <div class="item-info">
                            <div class="item-name"><?php echo $item['name']; ?></div>
                            <div class="item-color"><span class="color-label">Color:</span> Sample Color</div>
                        </div>
                        <div class="item-actions">
                            <div class="quantity-controls">
                                <button type="button" class="quantity-btn decrease">−</button>
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                       class="quantity-input" data-price="<?php echo $item['price']; ?>">
                                <button type="button" class="quantity-btn increase">+</button>
                            </div>
                            <a href="cart.php?action=remove&product_id=<?php echo $product_id; ?>" class="remove-btn" onclick="return confirm('Are you sure you want to remove this item?')">Remove</a>
                        </div>
                    </div>
                    <div class="item-price">₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                </div>
            <?php endforeach; ?>
            
            <div class="promotion-banner">
                <span class="promo-icon">%</span>
                <span class="promo-text">Want 800 PHP off? Enter <strong>ILOVEDWEB</strong> at checkout and enjoy the savings!</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="summary-container">
        <div class="order-summary">
            <h2 class="summary-title">Order Summary</h2>
            <div class="summary-row">
                <div class="summary-label">Subtotal</div>
                <div class="summary-value" id="total-price">₱<?php echo number_format($subtotal, 2); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Discount</div>
                <div class="summary-value">-₱<?php echo number_format($discount, 2); ?></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Shipping</div>
                <div class="summary-value free-text">Free</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Coupon Applied</div>
                <div class="summary-value" id="coupon-discount">-₱<?php echo number_format($coupon_discount, 2); ?></div>
            </div>
            <div class="summary-row total-row">
                <div class="summary-label">TOTAL</div>
                <div class="summary-value" id="grand-total">₱<?php echo number_format($total, 2); ?></div>
            </div>
            <form method="post" action="cart.php">
                <div class="coupon-input">
                    <input type="text" name="coupon_code" placeholder="Coupon Code" class="coupon-field" id="coupon-code" value="<?php echo isset($_SESSION['coupon_applied']) && $_SESSION['coupon_applied'] ? 'ILOVEDWEB' : ''; ?>">
                    <button type="submit" class="coupon-btn" id="apply-coupon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 5L16 12L9 19" stroke="#5d4b3e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </form>
            <form action="cart.php" method="post" id="checkout-form">
                <input type="hidden" name="checkout" value="1">
                <input type="hidden" name="cart_total" value="<?php echo $total; ?>">
                <button type="submit" class="checkout-btn">Proceed to Checkout</button>
            </form>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-brand">
            <h2>Splatter</h2>
            <p>Crafting comfort, one piece at a time.</p>
            <p>1st Street, Ballbogo, Angeles City, Pampanga, 2008, Philippines</p>
        </div>
        <div class="footer-links">
            <table>
                <thead>
                    <tr>
                        <th>Quick Links</th>
                        <th>Cart</th>
                        <th>Social</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="blog.php">Blog</a></td>
                        <td><a href="cart.php">Check Cart</a></td>
                        <td><a href="https://www.castlery.com/us">Facebook</a></td>
                    </tr>
                    <tr>
                        <td><a href="about.php">About Us</a></td>
                        <td><a href="checkout.php">Checkout</a></td>
                        <td><a href="https://www.instagram.com/castleryus/">Instagram</a></td>
                    </tr>
                    <tr>
                        <td><a href="contact.php">Contact Us</a></td>
                        <td></td>
                        <td><a href="https://www.linkedin.com/company/castlery-com/?originalSubdomain=sg">LinkedIn</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const input = this.parentElement.querySelector('.quantity-input');
            let currentValue = parseInt(input.value);
            let newValue;

            if (this.classList.contains('decrease')) {
                // Prevent going below 1
                newValue = Math.max(1, currentValue - 1);
            } else if (this.classList.contains('increase')) {
                // Optional: Set a max quantity if needed
                newValue = currentValue + 1;
            }

            // Update input value
            input.value = newValue;

            // Submit form to update cart
            updateCartItem(input);
        });
    });

    function updateCartItem(input) {
        const cartItem = input.closest('.cart-item');
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'cart.php';
        
        // Create hidden inputs for all necessary data
        const inputs = [
            { name: 'product_id', value: cartItem.dataset.id },
            { name: 'quantity', value: input.value },
            { name: 'product_name', value: cartItem.querySelector('.item-name').textContent },
            { name: 'product_price', value: cartItem.dataset.price },
            { name: 'product_image', value: cartItem.querySelector('.item-image').src }
        ];

        inputs.forEach(inputData => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = inputData.name;
            hiddenInput.value = inputData.value;
            form.appendChild(hiddenInput);
        });

        document.body.appendChild(form);
        form.submit();
    }

    // Flash message display
    <?php if ($flash_message): ?>
        const flashMessage = document.createElement('div');
        flashMessage.className = 'flash-message';
        flashMessage.textContent = '<?php echo addslashes($flash_message); ?>';
        flashMessage.style.display = 'block';
        document.querySelector('.cart-container').prepend(flashMessage);
        
        setTimeout(() => {
            flashMessage.style.display = 'none';
        }, 3000);
    <?php endif; ?>
});
</script>
</body>
</html>