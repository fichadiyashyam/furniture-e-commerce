<?php
session_start();
require_once '../config/db_config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to place an order.']);
    exit;
}

$user_id = intval($_SESSION['user_id']);

// 1. Get the user's cart
$cart_query = "SELECT id FROM carts WHERE user_id = $user_id LIMIT 1";
$cart_res = mysqli_query($connection, $cart_query);

if (!$cart_res || mysqli_num_rows($cart_res) === 0) {
    echo json_encode(['success' => false, 'message' => 'Your cart is fundamentally empty.']);
    exit;
}
$cart_id = mysqli_fetch_assoc($cart_res)['id'];

// 2. Fetch all cart items to calculate the total
$items_query = "SELECT ci.*, p.price FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = $cart_id";
$items_res = mysqli_query($connection, $items_query);

if (!$items_res || mysqli_num_rows($items_res) === 0) {
    echo json_encode(['success' => false, 'message' => 'Your cart is physically empty. Add items before placing an order.']);
    exit;
}

$total_amount = 0;
$items = [];
while ($row = mysqli_fetch_assoc($items_res)) {
    $total_amount += ($row['price'] * $row['qty']);
    $items[] = $row;
}

// 3. Create the order
$create_order = "INSERT INTO orders (user_id, total_amount, status) VALUES ($user_id, $total_amount, 'pending')";
if (mysqli_query($connection, $create_order)) {
    $order_id = mysqli_insert_id($connection);

    // 4. Insert order items
    foreach ($items as $item) {
        $p_id = $item['product_id'];
        $qty = $item['qty'];
        $price = $item['price'];
        $color = mysqli_real_escape_string($connection, $item['color'] ?? '');
        
        $insert_item = "INSERT INTO order_items (order_id, product_id, qty, price, color) VALUES ($order_id, $p_id, $qty, $price, '$color')";
        mysqli_query($connection, $insert_item);
    }

    // 5. Clear the user's cart
    mysqli_query($connection, "DELETE FROM cart_items WHERE cart_id = $cart_id");

    echo json_encode(['success' => true, 'message' => 'Order placed successfully! Pending approval.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create order. Please try again.']);
}
?>
