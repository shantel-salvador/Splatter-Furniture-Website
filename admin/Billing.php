<?php
session_start(); // Start the session

// Connect to the database
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'hau_store';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch the session ID from the CartItems table
$session_id = session_id();
$session_query = "SELECT SessionID FROM CartItems WHERE SessionID = ?";
$stmt = $mysqli->prepare($session_query);
$stmt->bind_param('s', $session_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($session_id);
    $stmt->fetch();
} else {
    $session_id = 'No active session'; // Handle case where no session is found
}
$stmt->close();

// Search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM billing WHERE FirstName LIKE ? OR LastName LIKE ? OR Contact LIKE ? OR Country LIKE ? OR Address LIKE ? OR Town LIKE ? OR Province LIKE ? OR ZipCode LIKE ? OR Email LIKE ?";
    $stmt = $mysqli->prepare($sql);
    $search_term = '%' . $search . '%';
    $stmt->bind_param('sssssssss', $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM billing";
    $result = $mysqli->query($sql);
}

// Handle deletion
if (isset($_GET['delete'])) {
    $billingID = $_GET['delete'];
    $deleteSQL = "DELETE FROM billing WHERE BillingID = ?";
    $stmt = $mysqli->prepare($deleteSQL);
    $stmt->bind_param('i', $billingID);
    $stmt->execute();
    header("Location: Billing.php"); // Redirect to reload the page after deletion
}

// Handling Data Insertion:
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insert'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $contact = $_POST['contact'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $town = $_POST['town'];
    $province = $_POST['province'];
    $zipCode = $_POST['zipCode'];
    $email = $_POST['email'];
    $totalPrice = $_POST['totalPrice']; // Assuming you have a total price input
    $productName = $_POST['productName']; // New field for product name
    $quantity = $_POST['quantity']; // New field for quantity

    // Insert the data into the Billing table
    $sql = "INSERT INTO billing (FirstName, LastName, Contact, Country, Address, Town, Province, ZipCode, Email, TotalPrice, ProductName, Quantity) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ssssssssdssd', $firstName, $lastName, $contact, $country, $address, $town, $province, $zipCode, $email, $totalPrice, $productName, $quantity);
    $stmt->execute();

    // Redirect to reload the page
    header("Location: Billing.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="header.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f3ed;
            margin: 0;
            padding: 0;
            color: #5a4032;
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
        }

    td {
        text-align: center; /* Center all table content */
    }


        th {
            background-color: #5E4736;
            color: white;
            font-family: 'Poppins', sans-serif;
            text-align: center; /* Center table headers */

        }

        tr:nth-child(even) {
            background-color: #f4f1eb;
        }

        tr:hover {
            background-color: #e6dfd4;
        }

        .search-input {
            font-size: 16px;
            padding: 8px;
            margin-top: 10px;
            margin-bottom: 10px;
            width: 250px;
            border-radius: 4px;
            border: 1px solid #98724B;
            display: inline-block;
            margin-left: 10px;
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

            .search-input {
                width: 100%;
                margin-top: 10px;
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
    <h1>Admin Dashboard</h1>

    <form method="GET" action="Billing.php">
        <input class="search-input" type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="button" type="submit">Search</button>
    </form>

    <div class="InfoTable">
        <table>
            <tr>
                <th>Billing ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Contact</th>
                <th>Country</th>
                <th>Address</th>
                <th>Town</th>
                <th>Province</th>
                <th>Zip Code</th>
                <th>Email</th>
                <th>Total Price</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Session ID</th> <!-- New column for Session ID -->
                <th>Actions</th>
            </tr>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['BillingID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['FirstName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['LastName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Contact']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Country']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Town']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Province']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ZipCode']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TotalPrice']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ProductName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                    echo "<td>" . htmlspecialchars($session_id) . "</td>"; // Displaying the Session ID
                    echo "<td style='text-align:center;'>
                        <a href='viewBilling.php?id=" . $row['BillingID'] . "' class='button button1'>View</a>
                        <a href='updateBilling.php?id=" . $row['BillingID'] . "' class='button button1'>Update</a>
                        <a href='Billing.php?delete=" . $row['BillingID'] . "' class='button button1' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='15' style='text-align:center;'>No records found.</td></tr>";
            }
            $mysqli->close();
            ?>
        </table>
    </div>
</div>

</body>
</html>