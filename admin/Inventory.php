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

// Handle the Delete Item Logic
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM Inventory WHERE inventory_id = ?";
    $stmt = $mysqli->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully.'); window.location.href='inventory.php';</script>";
    } else {
        echo "<script>alert('Error deleting item.');</script>";
    }
    $stmt->close();
}

// Handle the Search Functionality
$searchQuery = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $sql = "SELECT * FROM Inventory WHERE name LIKE ? OR category LIKE ?";
    $stmt = $mysqli->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%"; // Use wildcard to match partial terms
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    // If no search query, fetch all items
    $sql = "SELECT * FROM Inventory";
    $stmt = $mysqli->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
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
        padding: 8px;
        color: #5a4032;
    }

    th {
        background-color: #5E4736;
        color: white;
        font-family: 'Poppins', sans-serif;
        text-align: center; 
    }

    td {
        text-align: center; 
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
        width: 250px;
        border-radius: 4px;
        border: 1px solid #98724B;
        font-family: 'Poppins', sans-serif;
    }

    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .search-container {
        display: flex;
        align-items: center;
        gap: 10px;
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
        }

        .action-buttons {
            flex-direction: column;
            gap: 10px;
        }

        .search-container {
            width: 100%;
        }
    }
</style>
</head>
<body>
<ul>
        <li><a href="Billing.php">Billing</a></li>
        <li><a href="Inventory.php">Inventory/Stocks</a></li>
        <li><a href="Accounts.php">Accounts</a></li>
        <li><a href="CustomersCart.php">CustomerCart</a></li>
    </ul>
        <!-- Search Bar -->
        <div class="RightSide">
    <h1>Admin Dashboard</h1>
    <!-- Container for Add Item and Search -->
    <div class="action-buttons">
        <!-- Add Item Button -->
        <button class="button button1" onclick="window.location.href='add_item.php'">Add Item</button>
        
        <!-- Search Bar -->
        <div class="search-container">
            <form method="GET" action="inventory.php" style="display: flex; align-items: center; gap: 10px;">
                <input class="search-input" type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button class="button" type="submit">Search</button>
            </form>
        </div>
    </div>


        <!-- Inventory Table -->
        <div class="InfoTable">
            <table>
                <tr>
                    <th>Inventory ID</th>
                    <th>Name</th>
                    <th>Stocks</th>
                    <th>Supplier</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['inventory_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['stocks']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['supplier']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                        echo "<td>
                                <a href='update_item.php?inventory_id=" . $row['inventory_id'] . "' class='button button1'>Update</a>
                                <a href='?delete_id=" . $row['inventory_id'] . "' class='button button1' onclick='return confirm(\"Are you sure you want to delete this item?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No records found.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
