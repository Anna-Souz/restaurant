<?php
session_start();
require 'db.php'; // Your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Get the order details
$item_id = $_POST['item_id'];
$quantity = $_POST['quantity'];
$user_id = $_SESSION['user_id'];

// Fetch the item's rate from the menu table
$stmt = $conn->prepare("SELECT rate FROM menu WHERE id = :item_id");
$stmt->bindParam(':item_id', $item_id);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($item) {
    $rate = $item['rate'];
    $total_price = $rate * $quantity;

    // Insert the order into an 'orders' table
    $order_query = "INSERT INTO orders (user_id, item_id, quantity, total_price) VALUES (:user_id, :item_id, :quantity, :total_price)";
    $order_stmt = $conn->prepare($order_query);
    $order_stmt->bindParam(':user_id', $user_id);
    $order_stmt->bindParam(':item_id', $item_id);
    $order_stmt->bindParam(':quantity', $quantity);
    $order_stmt->bindParam(':total_price', $total_price);

    try {
        $order_stmt->execute();
        echo "Order placed successfully! <a href='menu.php'>Go back to menu</a>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Item not found.";
}
?>
