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

$billingID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($billingID > 0) {
    $sql = "SELECT * FROM Billing WHERE BillingID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $billingID);
    $stmt->execute();
    $result = $stmt->get_result();
    $billing = $result->fetch_assoc();
} else {
    $billing = null; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="header.css">
    <title>View Billing Details</title>
    <style>
        /* Styling for the overall page layout */
bbody {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #f8f3ed;
    color: #5a4032;
    line-height: 1.6;
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
    top: 0;
    left: 0;
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
    min-height: 100vh;
}

h1 {
    color: #5a4032;
    border-bottom: 2px solid #98724B;
    padding-bottom: 10px;
    margin-bottom: 20px;
    font-family: 'Poppins', sans-serif;
    text-align: center;
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
    border: 1px solid #B09D89;
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

tr:nth-child(even) td {
    background-color: #f4f1eb;
}

tr:nth-child(odd) td {
    background-color: white;
}

tr:hover td {
    background-color: #e6dfd4;
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
    margin-top: 20px;
}

.button:hover {
    background-color: #8F7358;
}

.button1 {
    background-color: white;
    color: #5E4736;
    border: 2px solid #5E4736;
}

.button1:hover {
    background-color: #f4f1eb;
    color: #5E4736;
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

    .button {
        width: 100%;
        text-align: center;
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
    <h1>View Billing Details</h1>

    <?php if ($billing): ?>
        <div class="InfoTable">
            <table style="width:100%;">
                <tr>
                    <th>Billing ID</th>
                    <td><?php echo htmlspecialchars($billing['BillingID']); ?></td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td><?php echo htmlspecialchars($billing['FirstName']); ?></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><?php echo htmlspecialchars($billing['LastName']); ?></td>
                </tr>
                <tr>
                    <th>Contact</th>
                    <td><?php echo htmlspecialchars($billing['Contact']); ?></td>
                </tr>
                <tr>
                    <th>Country</th>
                    <td><?php echo htmlspecialchars($billing['Country']); ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo htmlspecialchars($billing['Address']); ?></td>
                </tr>
                <tr>
                    <th>Town</th>
                    <td><?php echo htmlspecialchars($billing['Town']); ?></td>
                </tr>
                <tr>
                    <th>Province</th>
                    <td><?php echo htmlspecialchars($billing['Province']); ?></td>
                </tr>
                <tr>
                    <th>Zip Code</th>
                    <td><?php echo htmlspecialchars($billing['ZipCode']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($billing['Email']); ?></td>
                </tr>
                <tr>
                    <th>Product Name</th>
                    <td><?php echo htmlspecialchars($billing['ProductName']); ?></td>
                </tr>
                <tr>
                    <th>Quantity</th>
                    <td><?php echo htmlspecialchars($billing['Quantity']); ?></td>
                </tr>
            </table>
        </div>
        <a href="Billing.php" class="button button1">Back</a>
    <?php else: ?>
        <p>No record found with the given BillingID.</p>
    <?php endif; ?>

</div>

</body>
</html>

<?php
$mysqli->close();
?>