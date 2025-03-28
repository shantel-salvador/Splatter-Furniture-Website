# Splatter-Furniture-Website

On this file, it will show you how to create tables in order for the project to work properly; this includes connecting data in the database
and interact with a relational database. It involves setting up a database, creating tables, inserting data into an inventory table, 
and managing information related to billing, cart items, inventory, and user signups.

Make sure to download XAMPP in order for you to Follow the steps.
https://www.apachefriends.org/download.html

If XAMPP is downloaded, make sure to open it and Look for "APACHE" and "MYSQL" and start them both.

For easier mode of creating the database, click SHELL.


On shell, type:

"mysql -u root"

Then, 
Create a database for the project:

"CREATE DATABASE hau_store;"

Select the database:
USE hau_store;

Create the Tables:

CREATE TABLE billing (
    BillingID INT(11) NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Contact VARCHAR(15) NOT NULL,
    Country VARCHAR(50) NOT NULL,
    Address VARCHAR(150) NOT NULL,
    Province VARCHAR(50) NOT NULL,
    ZipCode VARCHAR(10) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    SessionID VARCHAR(255) NOT NULL,
    TotalPrice DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    Town VARCHAR(255) DEFAULT NULL,
    ProductName VARCHAR(255) DEFAULT NULL,
    Quantity INT(11) DEFAULT NULL,
    PRIMARY KEY (BillingID)
);


CREATE TABLE cartitems (
    CartItemID INT(11) NOT NULL AUTO_INCREMENT,
    SessionID VARCHAR(255) NOT NULL,
    ProductID INT(11) NOT NULL,
    ProductName VARCHAR(255) NOT NULL,
    Quantity INT(11) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    ImagePath VARCHAR(255) DEFAULT NULL,
    Subtotal DECIMAL(10, 2) AS (Quantity * Price) STORED,
    delivery_date VARCHAR(10) DEFAULT NULL,
    PRIMARY KEY (CartItemID)
);


CREATE TABLE inventory (
    inventory_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    stocks INT(11) NOT NULL,
    supplier VARCHAR(100) DEFAULT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(100) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (inventory_id)
);


CREATE TABLE signup (
    SignupID INT(11) NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(100) NOT NULL,
    ContactNumber VARCHAR(15) NOT NULL,
    PRIMARY KEY (SignupID)
);


After Creating the tables needed paste this to shell.


INSERT INTO inventory (name, price, stocks, supplier, category, image) VALUES
('Austen Counter Stool', 8999.00, 10, NULL, 'General Shop', NULL),
('Joseph Bed, Walnut', 8999.00, 19, NULL, 'Beds', NULL),
('Crescent Bed', 18000.00, 5, NULL, 'Beds', NULL),
('Dawson Leather Bed', 15999.00, 2, NULL, 'Beds', NULL),
('Rochelle Performance Bouclé Bed', 23000.00, 9, NULL, 'Beds', NULL),
('Lexi Tufted Bed', 14599.00, 17, NULL, 'Beds', NULL),
('Claude Performance Fabric Bed', 12000.00, 9, NULL, 'Beds', NULL),
('Joseph Bed', 17000.00, 6, NULL, 'Beds', NULL),
('Dawson Bed', 10000.00, 8, NULL, 'Beds', NULL),
('Marlow Performance Bouclé Curve Sofa', 11999.00, 20, NULL, 'Sofas', NULL),
('Dawson Wide Chaise Sectional Sofa', 15999.00, 24, NULL, 'Sofas', NULL),
('Lucia Cane Sofa, White Wash', 13399.00, 12, NULL, 'Sofas', NULL),
('Fable Performance Fabric Sofa', 14500.00, 11, NULL, 'Sofas', NULL),
('Mori Performance Fabric Sofa', 17599.00, 3, NULL, 'Sofas', NULL),
('Marlow Performance Bouclé Armless 2 Seater Sofa', 14000.00, 21, NULL, 'Sofas', NULL),
('Marlow Curve Sofa', 17000.00, 15, NULL, 'Sofas', NULL),
('Auburn Performance Fabric Sectional Sofa', 15999.00, 30, NULL, 'Sofas', NULL),
('Isaac Leather Bumper Chaise Sectional Sofa', 13000.00, 16, NULL, 'General Shop', NULL),
('Dawson Pit-Sectional Sofa', 12000.00, 30, NULL, 'Sofas', NULL),
('Hamilton Leather Chaise Sectional Sofa', 12000.00, 22, NULL, 'Sofas', NULL),
('Auburn Performance Fabric Right Arm Chaise', 11000.00, 30, NULL, 'Sofas', NULL),
('Brooke Round Dining Table', 11000.00, 15, NULL, 'General Shop', NULL),
('Kelsey Counter Stool', 4700.00, 26, NULL, 'General Shop', NULL),
('Bradley TV Stand', 7000.00, 10, NULL, 'General Shop', NULL),
('Dawson Sofa', 13000.00, 5, NULL, 'General Shop', NULL),
('Hugg Nesting Side Table', 14000.00, 18, NULL, 'General Shop', NULL),
('Posey Shelf', 17000.00, 9, NULL, 'General Shop', NULL),
('Abbey Performance Bench', 14000.00, 20, NULL, 'General Shop', NULL),
('Abbey Performance Bouclé Bench', 3999.00, 12, NULL, 'Chairs', NULL),
('Colette Swivel Armchair', 3599.00, 16, NULL, 'Chairs', NULL),
('Cassidy Swivel Chair', 2999.00, 21, NULL, 'Chairs', NULL),
('Sloane Cane Chair', 1499.00, 4, NULL, 'Chairs', NULL),
('Thierry Chair', 5000.00, 30, NULL, 'Chairs', NULL),
('Mico Rattan Armchair', 7199.00, 26, NULL, 'Chairs', NULL),
('Desmond Rocking Armchair', 6000.00, 14, NULL, 'Chairs', NULL),
('Rio Outdoor Lounge Chair', 2999.00, 6, NULL, 'Outdoor Furniture', NULL),
('Malta Outdoor Sofa', 8500.00, 12, NULL, 'Outdoor Furniture', NULL),
('Olwen Coffee Table', 3500.00, 17, NULL, 'Outdoor Furniture', NULL),
('Bistro Round Foldable Table with 2 Newport Chairs Set', 14500.00, 20, NULL, 'Outdoor Furniture', NULL),
('Rio Outdoor Dining Bench', 1999.00, 8, NULL, 'Outdoor Furniture', NULL),
('Sierra Outdoor Dining Table', 6000.00, 10, NULL, 'Outdoor Furniture', NULL),
('Rio Outdoor Teak Square Side Table', 2500.00, 9, NULL, 'Outdoor Furniture', NULL),
('Rio Outdoor Teak Loveseat', 13999.00, 16, NULL, 'Outdoor Furniture', NULL),
('Rio Outdoor 3 Seater Sofa', 9000.00, 5, NULL, 'Outdoor Furniture', NULL),
('Malta Outdoor Round Drum Coffee Table', 6500.00, 30, NULL, 'Outdoor Furniture', NULL),
('Duncan Round Coffee Table', 2500.00, 23, NULL, 'Outdoor Furniture', NULL),
('Rio Outdoor Teak Bar Stool Set', 4000.00, 40, NULL, 'Outdoor Furniture', NULL);


Then,

UPDATE inventory
SET image = 'product1.jpg'  WHERE inventory_id = 1;
UPDATE inventory
SET image = 'bed1.jpg'  WHERE inventory_id = 2;
UPDATE inventory
SET image = 'bed2.jpg'  WHERE inventory_id = 3;
UPDATE inventory
SET image = 'bed3.jpg'  WHERE inventory_id = 4;
UPDATE inventory
SET image = 'bed4.jpg'  WHERE inventory_id = 5;
UPDATE inventory
SET image = 'bed5.jpg'  WHERE inventory_id = 6;
UPDATE inventory
SET image = 'bed6.jpg'  WHERE inventory_id = 7;
UPDATE inventory
SET image = 'bed7.jpg'  WHERE inventory_id = 8;
UPDATE inventory
SET image = 'bed8.jpg'  WHERE inventory_id = 9;
UPDATE inventory
SET image = 'sofaone.jpg'  WHERE inventory_id = 10;
UPDATE inventory
SET image = 'sofa2.jpg'  WHERE inventory_id = 11;
UPDATE inventory
SET image = 'sofa3.jpg'  WHERE inventory_id = 12;
UPDATE inventory
SET image = 'sofa4.jpg'  WHERE inventory_id = 13;
UPDATE inventory
SET image = 'sofa5.jpg'  WHERE inventory_id = 14;
UPDATE inventory
SET image = 'sofa6.jpg'  WHERE inventory_id = 15;
UPDATE inventory
SET image = 'sofa7.jpg'  WHERE inventory_id = 16;
UPDATE inventory
SET image = 'sofa8.jpg'  WHERE inventory_id = 17;
UPDATE inventory
SET image = 'sofa9.jpg'  WHERE inventory_id = 18;
UPDATE inventory
SET image = 'sofa10.jpg'  WHERE inventory_id = 19;
UPDATE inventory
SET image = 'sofa11.jpg'  WHERE inventory_id = 20;
UPDATE inventory
SET image = 'sofa12.jpg'  WHERE inventory_id = 21;
UPDATE inventory
SET image ='product2.jpg'  WHERE inventory_id = 22;
UPDATE inventory
SET image ='product3.jpg'  WHERE inventory_id = 23;
UPDATE inventory
SET image ='product4.jpg'  WHERE inventory_id = 24;
UPDATE inventory
SET image ='product5.jpg'  WHERE inventory_id = 25;
UPDATE inventory
SET image ='product6.jpg'  WHERE inventory_id = 26;
UPDATE inventory
SET image ='product7.jpg'  WHERE inventory_id = 27;
UPDATE inventory
SET image ='product8.jpg'  WHERE inventory_id = 28;
UPDATE inventory
SET image ='chair1.jpg'  WHERE inventory_id = 29;
UPDATE inventory
SET image ='chair2.jpg'  WHERE inventory_id = 30;
UPDATE inventory
SET image ='chair3.jpg'  WHERE inventory_id = 31;
UPDATE inventory
SET image ='chair4.jpg'  WHERE inventory_id = 32;
UPDATE inventory
SET image ='chair5.jpg'  WHERE inventory_id = 33;
UPDATE inventory
SET image ='chair6.jpg'  WHERE inventory_id = 34;
UPDATE inventory
SET image ='chair7.jpg'  WHERE inventory_id = 35;
UPDATE inventory
SET image ='furni1.jpg'  WHERE inventory_id = 36;
UPDATE inventory
SET image ='furni2.jpg'  WHERE inventory_id = 37;
UPDATE inventory
SET image ='furni3.jpg'  WHERE inventory_id = 38;
UPDATE inventory
SET image =furni4.jpg'  WHERE inventory_id = 39;
UPDATE inventory
SET image ='furni5.jpg'  WHERE inventory_id = 40;
UPDATE inventory
SET image ='furni6.jpg'  WHERE inventory_id = 41;
UPDATE inventory
SET image ='furni7.jpg'  WHERE inventory_id = 42;
UPDATE inventory
SET image ='furni8.jpg'  WHERE inventory_id = 43;
UPDATE inventory
SET image ='furni9.jpg'  WHERE inventory_id = 44;
UPDATE inventory
SET image ='furni10.jpg'  WHERE inventory_id = 45;
UPDATE inventory
SET image ='furni11.jpg'  WHERE inventory_id = 46;
UPDATE inventory
SET image ='furni12.jpg'  WHERE inventory_id = 47;



After Completing the steps the website is fully functional. Enjoy!
