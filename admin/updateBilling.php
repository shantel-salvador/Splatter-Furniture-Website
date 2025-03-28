<?php
// Start the session
session_start();

// Connect to the database
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'hau_store';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the BillingID from the query parameter
$billingID = $_GET['id'] ?? 0;

// Fetch the existing data to populate the form
$billing = null; // Ensure $billing is defined before the query

if ($billingID > 0) {
    $sql = "SELECT * FROM Billing WHERE BillingID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $billingID);
    $stmt->execute();
    $result = $stmt->get_result();
    $billing = $result->fetch_assoc(); // Store the result in $billing
}

// Handle the form submission to update the record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $billing) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $contact = $_POST['contact'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $town = $_POST['town'];
    $province = $_POST['province'];
    $zipCode = $_POST['zipCode'];
    $email = $_POST['email'];
    $productName = $_POST['productName']; // Assuming you want to include this
    $quantity = $_POST['quantity']; // Assuming you want to include this

    $updateSQL = "UPDATE Billing SET FirstName=?, LastName=?, Contact=?, Country=?, Address=?, Town=?, Province=?, ZipCode=?, Email=?, ProductName=?, Quantity=? WHERE BillingID=?";
    $stmt = $mysqli->prepare($updateSQL);
    $stmt->bind_param('sssssssssssi', $firstName, $lastName, $contact, $country, $address, $town, $province, $zipCode, $email, $productName, $quantity, $billingID);
    $stmt->execute();

    // Set success message in session
    $_SESSION['success_message'] = 'Billing details updated successfully!';

    // Redirect to Billing page
    header("Location: Billing.php");
    exit(); // Ensure no further code is executed after the redirect
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="header.css">
    <title>Update Billing Details</title>
 <style>
    /* Overall Body Styling */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f3ed;
        color: #5a4032;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: row;
        min-height: 100vh;
    }

    /* Sidebar Navigation Styling */
    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 220px;
        background-color: #8F7358;
        height: 100%;
        position: fixed;
        overflow-y: auto;
        top: 0;
    }

    li a {
        display: block;
        color: #ffffff;
        padding: 16px;
        text-decoration: none;
        font-size: 16px;
        font-family: 'Poppins', sans-serif;
        transition: background-color 0.3s ease;
    }

    li a:hover {
        background-color: #B09D89;
    }

    /* RightSide Content Styling */
    .RightSide {
        margin-left: 240px;
        padding: 20px;
        background-color: #f8f3ed;
        width: calc(100% - 240px);
        box-sizing: border-box;
    }

    /* Header Styling */
    h1 {
        color: #5a4032;
        font-family: 'Poppins', sans-serif;
        font-size: 32px;
        margin-bottom: 20px;
    }

    /* Form Styling */
    form {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(94, 71, 54, 0.1);
        padding: 20px;
        max-width: 600px;
        margin: 0 auto;
    }

    label {
        font-size: 16px;
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
        margin-bottom: 8px;
        display: block;
        color: #5E4736;
    }

    input[type="text"], input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 2px solid #98724B;
        border-radius: 4px;
        font-size: 16px;
        background-color: #f4f1eb;
        transition: border 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    input[type="text"]:focus, input[type="email"]:focus {
        border-color: #5E4736;
        outline: none;
        background-color: #ffffff;
    }

    button {
        background-color: #5E4736;
        color: #fff;
        border: none;
        padding: 12px 20px;
        font-size: 16px;
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
        text-align: center;
        cursor: pointer;
        border-radius: 4px;
        transition: background-color 0.3s;
        width: 100%;
    }

    button:hover {
        background-color: #8F7358;
    }

    /* Table Styling */
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
        padding: 12px;
        color: #5a4032;
    }

    th {
        background-color: #5E4736;
        color: #fff;
        font-family: 'Poppins', sans-serif;
        font-size: 18px;
    }

    tr:nth-child(even) {
        background-color: #f4f1eb;
    }

    tr:hover {
        background-color: #e6dfd4;
    }

    /* Responsive Styling */
    @media (max-width: 768px) {
        ul {
            width: 100%;
            height: auto;
            position: static;
        }

        .RightSide {
            margin-left: 0;
            padding: 10px;
            width: 100%;
        }

        h1 {
            font-size: 28px;
            text-align: center;
        }

        button {
            width: 100%;
        }

        form {
            padding: 15px;
            margin-top: 20px;
            width: 100%;
        }

        table {
            font-size: 14px;
        }
    }
</style>
</head>
<body>

<ul>
  <li><a href="CustomerOrders.php">Customer Orders</a></li>
  <li><a href="Billing.php">Billing</a></li>
  <li><a href="inventory.php">Inventory/Stocks</a></li>
  <li><a href="Accounts.php">Accounts</a></li>
</ul>

<div class="RightSide">
    <h1>Update Billing Details</h1>

    <?php if ($billing): ?>
        <form method="POST">
            <label for="firstName">First Name</label>
            <input type="text" name="firstName" value="<?php echo htmlspecialchars($billing['FirstName']); ?>" required><br>

            <label for="lastName">Last Name</label>
            <input type="text" name="lastName" value="<?php echo htmlspecialchars($billing['LastName']); ?>" required><br>

            <label for="contact">Contact</label>
            <input type="text" name="contact" value="<?php echo htmlspecialchars($billing['Contact']); ?>" required><br>

            <label for="country">Country</label>
            <input type="text" name="country" value="<?php echo htmlspecialchars($billing['Country']); ?>" required><br>

            <label for="address">Address</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($billing['Address']); ?>" required><br>

            <label for="town">Town</label>
            <input type="text" name="town" value="<?php echo htmlspecialchars($billing['Town']); ?>" required><br>

            <label for="province">Province</label>
            <input type="text" name="province" value="<?php echo htmlspecialchars($billing['Province']); ?>" required><br>

            <label for="zipCode">Zip Code</label>
            <input type="text" name="zipCode" value="<?php echo htmlspecialchars($billing['ZipCode']); ?>" required><br>

            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($billing['Email']); ?>" required><br>

            <label for="productName">Product Name</label>
            <input type="text" name="productName" value="<?php echo htmlspecialchars($billing['ProductName']); ?>" required><br>

            <label for="quantity">Quantity</label>
            <input type="text" name="quantity" value="<?php echo htmlspecialchars($billing['Quantity']); ?>" required><br>

            <button type="submit" class="button">Update</button>
        </form>
    <?php else: ?>
        <p>No record found with the given BillingID.</p>
    <?php endif; ?>

</div>

</body>
</html>

<?php
$mysqli->close();
?>