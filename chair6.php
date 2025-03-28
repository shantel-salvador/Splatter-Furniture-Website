<?php
include 'db_connect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve inventory_id for the product name
$sql = "SELECT inventory_id FROM inventory WHERE name = 'Thierry Chair';";
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
    <title>Furniture Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Besley:ital,wght@0,400..900;1,400..900&family=DM+Serif+Text:ital@0;1&family=Montaga&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Volkhov:ital,wght@0,400;0,700;1,400;1,700&family=Yeseva+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="products.css">
    <link rel="stylesheet" href="header.css">

</head>
<body>
    <header>
        <div class="nav-bar">
            <div class="logo">splatter</div>
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
                <a href="cart.html" class="icon-link">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <a href="login.html" class="icon-link">
                    <i class="fas fa-user"></i> 
                </a>
            </div>
        </div>
    </header>
    <div class="breadcrumb">
        <a href="home.php">Home</a> > <a href="Chairs.php">Chairs</a> > Thierry Chair
    </div>

    <section class="product-container">
        <div class="product-details">
            <div class="product-header">
            <h1><?php echo htmlspecialchars($product['name'] ?? 'No Name'); ?></h1>
                <div class="price-rating">
                    <div class="price-container">
                        <span class="price">PHP 5,000</span>             
                    </div>
                    <div class="rating">
                        <span class="stars">★★★★<span class="half-star">★</span></span>
                        <span class="rating-text">4.8 / 5.0 (251)</span>
                    </div>
                </div>
            </div>
            <div class="stock-info">
                <p><strong>Available Stock: <?php echo isset($product['stocks']) ? $product['stocks'] : 'Out of stock'; ?></strong></p>
            </div>
            <div class="description">
                <p>Crafted with a mix of wood and metal elements, Thierry's curved bentwood backrest features a cutout design for a sophisticated touch.​​</p>
                <p>Combining modern sophistication, premium upholstery, and a sleek wood-metal contrast, this chestnut oak veneer seating is perfect for adding elegance and comfort to any space.</p>
            </div>
            <div class="purchase-actions">

            <form action="cart.php" method="post" style="display: flex; align-items: center;">
        <div class="quantity-selector" style="display: flex; align-items: center; border: 1px solid #ccc; border-radius: 5px; overflow: hidden; margin-right: 10px;">
            <button type="button" class="quantity-btn decrease" style=" border: none; padding: 5px 10px; cursor: pointer; font-size: 16px;">−</button>
            <input type="text" class="quantity-input" name="quantity" value="1" style="width: 50px; text-align: center; border: none; outline: none;">
            <button type="button" class="quantity-btn increase" style=" border: none; padding: 5px 10px; cursor: pointer; font-size: 16px;">+</button>
        </div>
        <input type="hidden" name="product_id" value="100">
        <input type="hidden" name="product_name" value="Thierry Chair">
        <input type="hidden" name="product_price" value="5000">
        <input type="hidden" name="product_image" value="chair6.jpg">
        <button type="submit" class="add-to-cart-btn" onclick="showFlashMessage()" style="margin-left: 10px; background-color: #5E4736; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Add to Cart</button>
    </form>
</div>
            <p class="shipping-info">Free 3-5 day shipping • Tool-free assembly • 30-day trial</p>
        </div>
        <div class="main-product-image">
            <img src="chair6.jpg" alt="Austen Counter Stool">
        </div>
    </section>
    
    <div class="divider"></div>

    <section class="additional-info">
        <h2>Additional Information</h2>
        
        <div class="info-content">
            <p>
            This upholstered seating blends contemporary design with premium materials for a sophisticated and comfortable experience. The frame is crafted from bentwood with an oak veneer, ensuring both durability and a refined natural aesthetic. The metal legs, finished in a matte black powder coating, provide a sleek contrast and stable support.           </p>
            
            <p>
            The seat is filled with foam and fiber, offering a plush and supportive feel. Upholstered in a high-quality fabric blend of 30% acrylic and 70% polyester, it delivers a soft yet durable texture designed for long-term use. The chestnut lacquer finish on the wood enhances its rich, warm tones, making it a versatile addition to various interior styles.           </p>

            <p>
            With its fixed cover design, this seating option is easy to maintain while retaining a clean, seamless look.            </p>
        </div>
        
        <div class="info-image">
            <img src="chair-6.jpg" alt="White Couch with Pillows">
        </div>
    </section>
    
    <section class="related-products">
        <h2>Related products</h2>
        
        <div class="product-grid">
        <div class="product-card">
                <a href="chair2.php">
                    <div class="related-product-image">
                        <img src="chair2.jpg" alt="Aurelie Furniture">
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
                        <img src="chair5.jpg" alt="Annasthacia Furniture">
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
                    <img src="chair4.jpg" alt="Cappucine Furniture">
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
                        <img src="chair7.jpg" alt="Benoite Furniture">
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
</html>