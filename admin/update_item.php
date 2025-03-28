<?php
// Connect to the database
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'hau_store';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the inventory item id from the query string
if (isset($_GET['inventory_id'])) {
    $inventory_id = $_GET['inventory_id'];

    // Fetch the current data for the item
    $sql = "SELECT * FROM Inventory WHERE inventory_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $inventory_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    // Handle form submission to update item
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $stocks = $_POST['stocks'];
        $supplier = $_POST['supplier'];
        $price = $_POST['price'];
        $category = $_POST['category'];

        // Update the item in the database
        $update_sql = "UPDATE Inventory SET name = ?, stocks = ?, supplier = ?, price = ?, category = ? WHERE inventory_id = ?";
        $update_stmt = $mysqli->prepare($update_sql);
        $update_stmt->bind_param("sisdsi", $name, $stocks, $supplier, $price, $category, $inventory_id);

        if ($update_stmt->execute()) {
            echo "<script>alert('Item updated successfully!'); window.location.href='inventory.php';</script>";
        } else {
            echo "<script>alert('Error updating item: " . $update_stmt->error . "');</script>";
        }

        $update_stmt->close();
    }

    $stmt->close();
} else {
    echo "<script>alert('Item ID is missing!'); window.location.href='inventory.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Inventory Item</title>
    <style>
        /* Global Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f3ed;
    color: #5a4032;
    margin: 0;
    padding: 0;
}

.container {
    width: 50%;
    margin: 5% auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(94, 71, 54, 0.1);
}

/* Typography */
h3 {
    text-align: center;
    color: #5a4032;
    font-family: 'Poppins', sans-serif;
}

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #5a4032;
    font-family: 'Poppins', sans-serif;
}

input, select {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #98724B;
    border-radius: 4px;
    font-family: 'Poppins', sans-serif;
}

/* Button Styles */
button {
    padding: 12px 20px;
    font-size: 16px;
    background-color: #5E4736;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

button:hover {
    background-color: #8F7358;
}

.back-button {
    background-color: white;
    color: #5E4736;
    border: 2px solid #5E4736;
}

.back-button:hover {
    background-color: #f4f1eb;
    color: #5E4736;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 90%;
        margin: 5% auto;
    }

    input, select, button {
        width: 100%;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <h3>Update Inventory Item</h3>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
            
            <label for="stocks">Stocks:</label>
            <input type="number" id="stocks" name="stocks" value="<?php echo htmlspecialchars($item['stocks']); ?>" required>
            
            <label for="supplier">Supplier:</label>
            <input type="text" id="supplier" name="supplier" value="<?php echo htmlspecialchars($item['supplier']); ?>">
            
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>
            
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="General Shop">General Shop</option>
                <option value="Sofas">Sofas</option>
                <option value="Beds">Beds</option>
                <option value="Chairs">Chairs</option>
                <option value="Outdoor Furniture">Outdoor Furniture</option>
            </select>
            
            <button type="submit">Update Item</button>
            <button type="button" class="back-button" onclick="window.location.href='inventory.php'">Back to Inventory</button>
        </form>
    </div>
</body>
</html>
