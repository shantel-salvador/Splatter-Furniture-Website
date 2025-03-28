<?php
include 'db_connect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve inventory_id for the product name
$sql = "SELECT inventory_id FROM inventory WHERE name = 'Abbey Performance Bouclé Bench';";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $product_id = $row['inventory_id'];
} else {
    die("Error: Product not found in inventory!");
}

// Prepare the statement to fetch product details
$stmt = $conn->prepare("SELECT name, price, stocks FROM inventory WHERE inventory_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Besley:ital,wght@0,400..900;1,400..900&family=DM+Serif+Text:ital@0;1&family=Montaga&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Volkhov:ital,wght@0,400;0,700;1,400;1,700&family=Yeseva+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="products.css">
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

<div class="breadcrumb">
    <a href="home.php">Home</a> > <a href="Chairs.php">Chairs</a> > <?php echo htmlspecialchars($product['name']); ?>
</div>

<section class="product-container">
    <div class="product-details">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <div class="price-rating">
            <div class="price-container">
                <span class="original-price">PHP <?php echo htmlspecialchars($product['price'] + 1000); // Example original price ?></span>
                <span class="price">PHP <?php echo htmlspecialchars($product['price']); ?></span>
            </div>
            <div class="rating">
                <span class="stars">★★★★<span class="half-star">★</span></span>
                <span class="rating-text">4.9 / 5.0 (689)</span>
            </div>
        </div>
        <div class="stock-info">
            <p><strong>Available Stock: <?php echo isset($product['stocks']) ? $product['stocks'] : 'Out of stock'; ?></strong></p>
        </div>
        <div class="description">
            <p>A chic addition to the end of your bed, in the dining room for extra guests or a comfy entrance bench to slip on your shoes. The Abbey Bench can handle it all with its wooden slab leg design and lasting performance bouclé.</p>
            <p>With its combination of high-quality materials, elegant finish, and sustainable packaging, this upholstered seating is a stylish and practical choice for any home.</p>
        </div>
        <div class="purchase-actions">
            <form action="cart.php" method="post" style="display: flex; align-items: center;">
        <div class="quantity-selector" style="display: flex; align-items: center; border: 1px solid #ccc; border-radius: 5px; overflow: hidden; margin-right: 10px;">
            <button type="button" class="quantity-btn decrease" style=" border: none; padding: 5px 10px; cursor: pointer; font-size: 16px;">−</button>
            <input type="text" class="quantity-input" name="quantity" value="1" style="width: 50px; text-align: center; border: none; outline: none;">
            <button type="button" class="quantity-btn increase" style=" border: none; padding: 5px 10px; cursor: pointer; font-size: 16px;">+</button>
        </div>
        <input type="hidden" name="product_id" value="251">
        <input type="hidden" name="product_name" value="Abbey Performance Bouclé Bench">
        <input type="hidden" name="product_price" value="3999">
        <input type="hidden" name="product_image" value="chair1.jpg">
        <button type="submit" class="add-to-cart-btn" onclick="showFlashMessage()" style="margin-left: 10px; background-color: #5E4736; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Add to Cart</button>
    </form>
</div>
        <p class="shipping-info">Free 3-5 day shipping • Tool-free assembly • 30-day trial</p>
    </div>
    <div class="main-product-image">
    <img src="chair1.jpg" alt="Austen Counter Stool">
    </div>
</section>

<div class="divider"></div>

<section class="additional-info">
    <h2>Additional Information</h2>
    <div class="info-content">
        <p>This upholstered seating blends durability, comfort, and elegant design, making it a versatile addition to any space. The seat frame is crafted from solid ash, ensuring strength and longevity, while the interior structure features engineered wood, plywood, and PU foam, providing both stability and plush support. The legs, made from engineered wood with an oak veneer, enhance the overall aesthetic with a natural and sophisticated touch.</p>
        <p>Finished in a rich chestnut oak, this piece exudes warmth and timeless appeal, seamlessly complementing various interior styles. Designed for easy care and maintenance, it ensures long-lasting comfort and durability.</p>
        <p>For a more eco-conscious choice, this seating is packaged free of Styrofoam, reducing environmental impact.</p>
    </div>
</section>

<section class="related-products">
    <h2>Related products</h2>
    <div class="product-grid">
        <div class="product-card">
            <a href="chair2.php">
                <div class="related-product-image">
                    <img src="chair2.jpg" alt="Colette Swivel Armchair">
                </div>
                <h3>Colette Swivel Armchair</h3>
                <div class="product-rating">
                    <span class="stars">★★★★★</span>
                </div>
            </a>
        </div>
        <div class="product-card">
            <a href="chair5.php">
                <div class="related-product-image">
                    <img src="chair5.jpg" alt="Sloane Cane Chair">
                </div>
                <h3>Sloane Cane Chair</h3>
                <div class="product-rating">
                    <span class="stars">★★★★★</span>
                </div>
            </a>
        </div>
        <div class="product-card">
            <a href="chair4.php">
                <div class="related-product-image">
                    <img src="chair4.jpg" alt="Cassidy Swivel Chair">
                </div>
                <h3>Cassidy Swivel Chair</h3>
                <div class="product-rating">
                    <span class="stars">★★★★★</span>
                </div>
            </a>
        </div>
        <div class="product-card">
            <a href="chair7.php">
                <div class="related-product-image">
                    <img src="chair7.jpg" alt="Mico Rattan Armchair">
                </div>
                <h3>Mico Rattan Armchair</h3>
                <div class="product-rating">
                    <span class="stars">★★★★★</span>
                </div>
            </a>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity selector functionality
        const decreaseBtn = document.querySelector('.decrease');
        const increaseBtn = document.querySelector('.increase');
        const quantityInput = document.querySelector('.quantity-input');
        
        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        
        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        });
    });
</script>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-brand">
            <h2 class="windsong-logo">Splatter</h2>
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
</body>
</html>