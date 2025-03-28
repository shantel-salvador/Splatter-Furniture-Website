<?php
include 'db_connect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve inventory_id for the product name
$sql = "SELECT inventory_id FROM inventory WHERE name = 'Lexi Tufted Bed';";
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
        <a href="home.php">Home</a> > <a href="Beds.php">Beds</a> > Lexi Tufted Bed
    </div>

    <section class="product-container">
        <div class="product-details">
            <div class="product-header">
                <h1><?php echo htmlspecialchars($product['name'] ?? 'No Name'); ?></h1>
                <div class="price-rating">
                    <div class="price-container">
                        <span class="original-price">PHP 16,000</span>
                        <span class="price">PHP 14,599</span>             
                    </div>
                    <div class="rating">
                        <span class="stars">★★★★<span class="half-star">★</span></span>
                        <span class="rating-text">4.9 / 5.0 (179)</span>
                    </div>
                </div>
            </div>
            <div class="stock-info">
                <p><strong>Available Stock: <?php echo isset($product['stocks']) ? $product['stocks'] : 'Out of stock'; ?></strong></p>
            </div>
            <div class="description">
                <p>Start and end the day in bliss. Lexi's gentle curves and padded headboard offer just the right amount of support and comfort.​​</p>
                <p>Due to natural color variance, each bed frame may have slight differences in appearance, adding to its unique character. The headboard backing is made of non-woven fabric, maintaining a clean and polished look.</p>
            </div>
            <div class="purchase-actions">
            <form action="cart.php" method="post" style="display: flex; align-items: center;">
        <div class="quantity-selector" style="display: flex; align-items: center; border: 1px solid #ccc; border-radius: 5px; overflow: hidden; margin-right: 10px;">
            <button type="button" class="quantity-btn decrease" style=" border: none; padding: 5px 10px; cursor: pointer; font-size: 16px;">−</button>
            <input type="text" class="quantity-input" name="quantity" value="1" style="width: 50px; text-align: center; border: none; outline: none;">
            <button type="button" class="quantity-btn increase" style=" border: none; padding: 5px 10px; cursor: pointer; font-size: 16px;">+</button>
        </div>
        <input type="hidden" name="product_id" value="33">
        <input type="hidden" name="product_name" value="Lexi Tufted Bed">
        <input type="hidden" name="product_price" value="14599">
        <input type="hidden" name="product_image" value="bed5.jpg">
        <button type="submit" class="add-to-cart-btn" onclick="showFlashMessage()" style="margin-left: 10px; background-color: #5E4736; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Add to Cart</button>
    </form>
</div>
            <p class="shipping-info">Free 3-5 day shipping • Tool-free assembly • 30-day trial</p>
        </div>
        <div class="main-product-image">
            <img src="bed5.jpg" alt="Austen Counter Stool">
        </div>
    </section>
    
    <div class="divider"></div>

    <section class="additional-info">
        <h2>Additional Information</h2>
        
        <div class="info-content">
            <p>
            Combining elegance with durability, this upholstered bed frame is designed for both style and comfort. The frame is crafted from sturdy plywood, ensuring long-lasting support, while the leg frame is made of rubber wood with a metal cap, adding a sophisticated touch. The wood is finished with a walnut stain, while the metal accents feature a brass stain, creating a refined and timeless aesthetic.
            </p>
            
            <p>
            The plush headboard is upholstered in 100% polyester fabric, offering a soft yet durable texture. Filled with foam and fiber, it provides excellent cushioning for added comfort. The plywood bed slats ensure even mattress support, enhancing durability and stability.            
            </p>

            <p>
            <b>Prop 65 Warning:</b> This product contains <i>formaldehyde</i>, a substance known to the State of California to cause certain health risks. Proper ventilation is recommended.            </p>
        </div>
        
        <div class="info-image">
            <img src="bed-5.jpg" alt="White Couch with Pillows">
        </div>
    </section>
    
    <section class="related-products">
        <h2>Related products</h2>
        
        <div class="product-grid">
            <div class="product-card">
                <a href="bed5.php">
                    <div class="related-product-image">
                        <img src="bed5.jpg" alt="Aurelie Furniture">
                    </div>
                    <h3>Lexi Tuffed Bed</h3>
                    <div class="product-rating">
                        <span class="stars">★★★★★</span>
                    </div>
                </a>
            </div>
            
            <div class="product-card">
                <a href="bed6.php">
                    <div class="related-product-image">
                        <img src="bed6.jpg" alt="Annasthacia Furniture">
                    </div>
                    <h3>Claude Performance Fabric Bed</h3>
                    <div class="product-rating">
                        <span class="stars">★★★★★</span>
                    </div>
                </a>
            </div>
            
            <div class="product-card">
                <a href="bed7.php">
                    <div class="related-product-image">
                    <img src="bed7.jpg" alt="Cappucine Furniture">
                    </div>
                    <h3>Joseph Bed</h3>
                    <div class="product-rating">
                        <span class="stars">★★★★★</span>
                    </div>
                </a>
            </div>
            
            <div class="product-card">
                <a href="bed8.php">
                    <div class="related-product-image">
                        <img src="bed8.jpg" alt="Benoite Furniture">
                    </div>
                    <h3>Dawson Bed</h3>
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
</body>
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