<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HAU STORE - Admin</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    overflow-x: hidden;
    background-color: #f8f3ed;
    color: #5a4032;
}

.container {
    background: url('shantel.jfif') no-repeat center center/cover;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    background-color: #B09D89;
    color: white;
    box-shadow: 0 2px 5px rgba(94, 71, 54, 0.1);
}

.header h2 a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    font-size: 1.5rem;
    font-family: 'Poppins', sans-serif;
}

.header nav {
    display: flex;
    gap: 1rem;
    list-style: none;
}

.header nav a {
    color: white;
    text-decoration: none;
    font-weight: 300;
    font-size: 1rem;
    padding: 0.5rem 1rem;
    transition: background-color 0.3s ease;
    font-family: 'Poppins', sans-serif;
    border-radius: 5px;
}

.header nav a:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.content {
    text-align: center;
    color: white;
    /* Remove margin-top: auto to keep content centered */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex-grow: 1;
}

.content h1 {
    font-size: 12rem;
    margin-bottom: 1rem;
    font-family: 'Besley', serif;
    color: white;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    text-align: center;
    width: 100%; /* Ensure full width for centering */
}

.content p {
    background-color: rgba(94, 71, 54, 0.8);
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    font-size: 1.2rem;
    font-weight: 300;
    font-family: 'Poppins', sans-serif;
}

.quote-section {
    text-align: center;
    margin: 2rem 0;
    color: white;
}

.quote-section img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 1rem;
}

.quote-section p {
    font-size: 1.5rem;
    font-weight: 400;
    margin-top: 1rem;
    color: #ffffff;
    font-family: 'Poppins', sans-serif;
}

.footer {
    text-align: center;
    background-color: #8F7358;
    color: white;
    font-size: 0.9rem;
    padding: 0.7rem;
    font-family: 'Poppins', sans-serif;
}

@media (max-width: 768px) {
    .header h2 a {
        font-size: 1.2rem;
    }

    .content h1 {
        font-size: 4rem;
    }

    .content p {
        font-size: 1rem;
    }

    .header nav a {
        font-size: 0.9rem;
        padding: 0.5rem;
    }

    .quote-section p {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .header nav {
        flex-direction: column;
        align-items: flex-start;
    }

    .content h1 {
        font-size: 3rem;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h2><a href="index.php">Splater Admin</a></h2>
            <nav>
                <li><a href="Billing.php">Billing</a></li>
                <li><a href="Inventory.php">Inventory/Stocks</a></li>
                <li><a href="Accounts.php">Accounts</a></li>
                <li><a href="CustomersCart.php">CustomerCart</a></li>
                </nav>
        </header>

        <div class="content">
            <h1>Splater</h1>
            <p>You are currently on the admin dashboard</p>
        </div>


        <footer class="footer">
            &copy; 2025 HAU-STORE CORPORATION. All Rights Reserved.
        </footer>
    </div>
</body>
</html>