<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hau_store";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$total_price = 0;
$product_names = [];
$product_quantities = [];

$discount = 200;
$coupon_discount = 0;

if (isset($_SESSION['coupon_applied']) && $_SESSION['coupon_applied']) {
    $coupon_discount = 800;
}

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
        $product_names[] = $item['name'];
        $product_quantities[] = $item['quantity'];
    }
}

$product_names_string = implode(", ", $product_names);
$product_quantities_string = implode(", ", $product_quantities);
$final_price = $total_price - $discount - $coupon_discount;

$order_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place-order'])) {
    $first_name = isset($_POST['first-name']) ? $_POST['first-name'] : '';
    $last_name = isset($_POST['last-name']) ? $_POST['last-name'] : '';
    $country = isset($_POST['country']) ? $_POST['country'] : '';
    $street_address = isset($_POST['street-address']) ? $_POST['street-address'] : '';
    $town_city = isset($_POST['town-city']) ? $_POST['town-city'] : '';
    $province = isset($_POST['province']) ? $_POST['province'] : '';
    $zip_code = isset($_POST['zip-code']) ? $_POST['zip-code'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $session_id = session_id();

    if (empty($first_name) || empty($last_name) || empty($country) || empty($street_address) || empty($town_city) || empty($province) || empty($zip_code) || empty($phone) || empty($email)) {
        echo "<h3 style='color: red;'>Please fill in all required fields.</h3>";
    } else {
        $sql_billing = "INSERT INTO Billing (FirstName, LastName, Country, Address, Town, Province, ZipCode, Contact, Email, SessionID, TotalPrice, ProductName, Quantity)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_billing);

        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssssssssssss", $first_name, $last_name, $country, $street_address, $town_city, $province, $zip_code, $phone, $email, $session_id, $final_price, $product_names_string, $product_quantities_string);

        if (!$stmt->execute()) {
            echo "<h3 style='color: red;'>Error saving billing information: " . $stmt->error . "</h3>";
        } else {
            $order_success = true;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furniture Store</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="checkout.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Besley:ital,wght@0,400..900;1,400..900&family=DM+Serif+Text:ital@0;1&family=Montaga&family=Volkhov:ital,wght@0,400;0,700;1,400;1,700&family=Yeseva+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Besley:ital,wght@0,400..900;1,400..900&family=DM+Serif+Text:ital@0;1&family=Montaga&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Volkhov:ital,wght@0,400;0,700;1,400;1,700&family=Yeseva+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #f8f3ed;
        margin: 10% auto;
        padding: 60px;
        border-radius: 15px;
        width: 60%;
        max-width: 600px;
        box-shadow: 0 5px 30px rgba(94, 71, 54, 0.5);
        text-align: center;
        position: relative;
        border: 2px solid #8F7358;
    }

    .close {
        color: #aaa;
        position: absolute;
        top: 20px;
        right: 30px;
        font-size: 36px;
        font-weight: bold;
        transition: color 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #5E4736;
        text-decoration: none;
        cursor: pointer;
    }

    .success-icon {
        font-size: 80px;
        color: #6f4e37;
        margin-bottom: 30px;
        display: inline-block;
    }

    .modal-title {
        font-size: 32px;
        color: #5E4736;
        margin-bottom: 20px;
        font-family: 'Poppins', sans-serif;
    }

    .modal-message {
        font-size: 18px;
        color: #5E4736;
        margin-bottom: 30px;
        font-family: 'Poppins', sans-serif;
    }

    .modal-button {
        padding: 15px 40px;
        background-color: #8F7358;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 18px;
        font-weight: 500;
        transition: background-color 0.3s;
        border: none;
        cursor: pointer;
        display: inline-block;
        margin-top: 20px;
    }

    .modal-button:hover {
        background-color: #6f4e37;
    }
</style>
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
        <h1>Checkout</h1>
        <div class="breadcrumb">
            <a href="home.php">Home</a> > Checkout
        </div>
    </div>
</section>

<div class="billing-container">
    <div class="billing-details">
        <h1>Billing Details</h1>
        <form action="checkout.php" method="POST">
            <div class="form-group">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first-name" required>
            </div>

            <div class="form-group">
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last-name" required>
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" required>
            </div>

            <div class="form-group">
                <label for="street-address">Street Address</label>
                <input type="text" id="street-address" name="street-address" required>
            </div>

            <div class="form-group">
                <label for="town-city">Town/City</label>
                <input type="text" id="town-city" name="town-city" required>
            </div>

            <div class="form-group">
                <label for="province">Province</label>
                <input type="text" id="province" name="province" required>
            </div>

            <div class="form-group">
                <label for="zip-code">ZIP Code</label>
                <input type="text" id="zip-code" name="zip-code" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
        </div>

        <div class="order-summary">
            <h2>Order Summary</h2>
            <div class="order-items">
                <h3>Items in your cart:</h3>
                <ul>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <li><?php echo htmlspecialchars($item['name']) . " (Quantity: " . htmlspecialchars($item['quantity']) . ")"; ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No items in the cart.</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="total-section">
                <span>Subtotal</span>
                <span>₱<?php echo number_format($total_price, 2); ?></span>
            </div>
            <div class="total-section">
                <span>Standard Discount</span>
                <span>-₱<?php echo number_format($discount, 2); ?></span>
            </div>
            <div class="total-section">
                <span>Coupon Discount</span>
                <span>-₱<?php echo number_format($coupon_discount, 2); ?></span>
            </div>
            <div class="total-section">
                <span>Total</span>
                <span>₱<?php echo number_format($final_price, 2); ?></span>
            </div>
            <button type="submit" name="place-order" class="place-order">Place Order</button>
        </div>
    </form>
</div>

<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#6f4e37" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <h2 class="modal-title">Payment Success</h2>
        <p class="modal-message">Your order is confirmed! Thank you for your Purchase!</p>
        <a href='home.php' class='modal-button'>Continue Shopping</a>
    </div>
</div>
<script>
    function openModal() {
        document.getElementById('successModal').style.display = "block";
    }

    function closeModal() {
        document.getElementById('successModal').style.display = "none";
    }

    <?php if ($order_success): ?>
        openModal();
    <?php endif; ?>
</script>

</body>
</html>