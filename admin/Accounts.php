<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'hau_store';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM Signup WHERE SignupID = ?";
    $stmt = $mysqli->prepare($delete_sql);
    $stmt->bind_param('i', $delete_id);
    $stmt->execute();
    header("Location: Accounts.php"); 
    exit();
}

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM Signup WHERE FirstName LIKE ? OR LastName LIKE ? OR Email LIKE ? OR ContactNumber LIKE ?";
    $stmt = $mysqli->prepare($sql);
    $search_term = '%' . $search . '%';
    $stmt->bind_param('ssss', $search_term, $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM Signup";
    $result = $mysqli->query($sql);
}
?>

<!DOCTYPE html>
<html>
<style>
        /* Styling for the overall page layout */
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
<head>
    <link rel="stylesheet" href="header.css">
</head>
<body>
<ul>
        <li><a href="Billing.php">Billing</a></li>
        <li><a href="Inventory.php">Inventory/Stocks</a></li>
        <li><a href="Accounts.php">Accounts</a></li>
        <li><a href="CustomersCart.php">CustomerCart</a></li>
    </ul>
    <div class="RightSide">
        <h1>Admin Dashboard</h1>

        <form method="GET" action="Accounts.php">
        <input class="search-input" type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="button" type="submit">Search</button>
        </form>

        <div class="InfoTable">
            <table style="width:100%;">
              <tr>
                <th>Sign Up ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Contact Number</th>
                <th>Actions</th>
              </tr>
              <?php
              if ($result && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>" . htmlspecialchars($row['SignupID']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['FirstName']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['LastName']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['Password']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['ContactNumber']) . "</td>";
                      echo "<td style='text-align:center;'>
                              <a href='Accounts.php?delete_id=" . $row['SignupID'] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>
                                <button class='button button1'>Delete</button>
                              </a>
                            </td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='7' style='text-align:center;'>No records found.</td></tr>";
              }
              $mysqli->close();
              ?>
            </table>
        </div>
    </div>
</body>
</html>
