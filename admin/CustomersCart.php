<?php
// Start the session
session_start();

// Connect to the database
$host = 'localhost';
$dbname = 'hau_store';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$session_id = session_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_item'])) {
        $product_id = $_POST['product_id'];
        $delete_query = "DELETE FROM CartItems WHERE ProductID = :product_id AND SessionID = :session_id";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bindParam(':product_id', $product_id);
        $delete_stmt->bindParam(':session_id', $session_id);
        $delete_stmt->execute();
    }
}

$query = "SELECT * FROM CartItems WHERE SessionID = :session_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':session_id', $session_id);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f3ed;
        color: #5a4032;
        margin: 0;
        padding: 0;
    }

    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 200px;
        background-color: #8F7358;
        height: 100%;
        position: fixed;
        overflow: auto;
    }

    li a {
        display: block;
        color: white;
        padding: 14px;
        text-decoration: none;
        font-size: 16px;
        font-family: 'Poppins', sans-serif;
        transition: background-color 0.3s ease;
    }

    li a:hover {
        background-color: #B09D89;
    }

    .RightSide {
        margin-left: 220px;
        padding: 20px;
        background-color: #f8f3ed;
        height: 100%;
    }

    .RightSide h1 {
        color: #5a4032;
        font-family: 'Poppins', sans-serif;
    }

    .button {
        background-color: #5E4736;
        border: none;
        color: white;
        padding: 12px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-family: 'Poppins', sans-serif;
    }

    .button:hover {
        background-color: #8F7358;
    }

    .button1 {
        background-color: white;
        color: #5E4736;
        border: 2px solid #5E4736;
        font-family: 'Poppins', sans-serif;
    }

    .button1:hover {
        background-color: #f4f1eb;
        color: #5E4736;
    }

    .InfoTable {
        margin-top: 20px;
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        box-shadow: 0 2px 5px rgba(94, 71, 54, 0.1);
    }

    table, th, td {
        border: 1px solid #98724B;
        text-align: left;
        padding: 8px;
        color: #5a4032;
    }

    th {
        background-color: #5E4736;
        color: white;
        font-family: 'Poppins', sans-serif;
        text-align: center;
    }

    td{
        text-align: center;
    }

    tr:nth-child(even) {
        background-color: #f4f1eb;
    }

    tr:hover {
        background-color: #e6dfd4;
    }

    input[type="number"] {
        width: 60px;
        padding: 4px;
        border: 1px solid #98724B;
        border-radius: 4px;
        text-align: center;
        font-family: 'Poppins', sans-serif;
    }

    /* Total price styling */
    h3 {
        color: #5a4032;
        font-family: 'Poppins', sans-serif;
    }

    @media (max-width: 768px) {
        ul {
            width: 100%;
            height: auto;
            position: static;
        }

        .RightSide {
            margin-left: 0;
            padding: 10px;
        }

        table {
            font-size: 14px;
        }
    }
</style>
</head>
<body>
    <ul>
        <li><a href="Billing.php">Billing</a></li>
        <li><a href="Inventory.php">Inventory/Stocks</a></li>
        <li><a href="Accounts.php">Accounts</a></li>
        <li><a href="CustomersCart.php">Customer Cart</a></li>
    </ul>

    <div class="RightSide">
        <h1>Customers Cart</h1>
        <div class="InfoTable">
            <table>
                <thead>
                    <tr>
                        <th>Cart Item ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Session ID</th> <!-- Added Session ID header -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($cart_items) > 0): ?>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['CartItemID']) ?></td>
                                <td><?= htmlspecialchars($item['ProductName']) ?></td>
                                <td>₱<?= number_format($item['Price'], 2) ?></td>
                                <td>
                                    <input type="number" value="<?= htmlspecialchars($item['Quantity']) ?>" min="1" readonly>
                                </td>
                                <td>₱<?= number_format($item['Price'] * $item['Quantity'], 2) ?></td>
                                <td><?= htmlspecialchars($item['SessionID']) ?></td> <!-- Displaying Session ID -->
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="product_id" value="<?= $item['ProductID'] ?>">
                                        <button type="submit" name="remove_item" class="button button1">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">Your cart is empty.</td> <!-- Adjusted colspan -->
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
        $total_price_array = array_map(function ($item) {
            return $item['Price'] * $item['Quantity'];
        }, $cart_items);
        $total_price = array_sum($total_price_array);
        ?>
        <h3>Total: ₱<?= number_format($total_price, 2) ?></h3>
    </div>
</body>
</html>

<?php
$conn = null; // Close the database connection
?>