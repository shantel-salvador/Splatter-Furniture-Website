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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $stocks = $_POST['stocks'];
    $supplier = $_POST['supplier'];
    $price = $_POST['price'];
    $category = $_POST['category'];  // Get category from the form

    // Prepare the SQL query
    $sql = "INSERT INTO Inventory (name, stocks, supplier, price, category) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    // Corrected the bind_param to account for all five parameters
    $stmt->bind_param("sisss", $name, $stocks, $supplier, $price, $category); // 'sisss' for (string, integer, string, double, string)

    // Execute the query and handle success or failure
    if ($stmt->execute()) {
        echo "<script>alert('Item added successfully!'); window.location.href='inventory.php';</script>";
    } else {
        echo "<script>alert('Error adding item: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <style>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f3ed;
        color: #5a4032;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .container {
        width: 100%;
        max-width: 500px;
        margin: 5% auto;
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(94, 71, 54, 0.1);
    }

    h3 {
        text-align: center;
        color: #5a4032;
        font-family: 'Poppins', sans-serif;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
        color: #5E4736;
        font-family: 'Poppins', sans-serif;
    }

    input, select {
        padding: 10px;
        font-size: 16px;
        border: 2px solid #98724B;
        border-radius: 4px;
        background-color: #f4f1eb;
        font-family: 'Poppins', sans-serif;
        transition: border-color 0.3s ease, background-color 0.3s ease;
    }

    input:focus, select:focus {
        border-color: #5E4736;
        background-color: white;
        outline: none;
    }

    button {
        padding: 12px;
        font-size: 16px;
        background-color: #5E4736;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #8F7358;
    }

    .back-button {
        background-color: #98724B;
        margin-top: 15px;
    }

    .back-button:hover {
        background-color: #B09D89;
    }

    @media (max-width: 768px) {
        .container {
            width: 90%;
            margin: 5% auto;
            padding: 20px;
        }

        input, select, button {
            width: 100%;
            box-sizing: border-box;
        }
    }
</style>
</head>
<body>
    <div class="container">
        <h3>Add New Inventory Item</h3>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter item name" required>
            
            <label for="stocks">Stocks:</label>
            <input type="number" id="stocks" name="stocks" placeholder="Enter stock quantity" required>
            
            <label for="supplier">Supplier:</label>
            <input type="text" id="supplier" name="supplier" placeholder="Enter supplier name">
            
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" placeholder="Enter item price" required>
            
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="General Shop">General Shop</option>
                <option value="Sofas">Sofas</option>
                <option value="Beds">Beds</option>
                <option value="Chairs">Chairs</option>
                <option value="Outdoor Furniture">Outdoor Furniture</option>
            </select>
            
            <button type="submit">Add Item</button>
            <button type="button" class="back-button" onclick="window.location.href='inventory.php'">Back to Inventory</button>
        </form>
    </div>
</body>
</html>
