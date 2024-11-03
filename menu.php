<?php
session_start(); // Start the session
require 'db.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch menu items from the database
$stmt = $conn->prepare("SELECT * FROM menu");
$stmt->execute();
$menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu</title>
</head>
<body>
    <h1>Menu</h1>
    <table border="1">
        <tr>
            <th>Item Name</th>
            <th>Rate</th>
            <th>Order</th>
        </tr>
        <?php if (!empty($menu_items)): ?>
            <?php foreach ($menu_items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                <td><?php echo htmlspecialchars($item['rate']); ?></td>
                <td>
                    <form method="POST" action="order.php">
                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                        <input type="number" name="quantity" min="1" placeholder="Qty" required>
                        <button type="submit">Order</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No menu items available.</td>
            </tr>
        <?php endif; ?>
    </table>
    <a href="add_menu_item.php">Add Menu Item</a> <!-- Link to add new items -->
</body>
</html>
