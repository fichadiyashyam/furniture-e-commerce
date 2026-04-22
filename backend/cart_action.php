<?php
session_start();
require_once '../config/db_config.php';
header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : '';

$session_id = session_id();
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

// Get or create cart
function getCartId($connection, $user_id, $session_id) {
    if ($user_id) {
        $query = "SELECT id FROM carts WHERE user_id = $user_id LIMIT 1";
    } else {
        $safe_session = mysqli_real_escape_string($connection, $session_id);
        $query = "SELECT id FROM carts WHERE session_id = '$safe_session' LIMIT 1";
    }
    
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    }

    // Create new cart
    if ($user_id) {
        $insert = "INSERT INTO carts (user_id) VALUES ($user_id)";
    } else {
        $safe_session = mysqli_real_escape_string($connection, $session_id);
        $insert = "INSERT INTO carts (session_id) VALUES ('$safe_session')";
    }
    mysqli_query($connection, $insert);
    return mysqli_insert_id($connection);
}

function getTotalCount($connection, $cart_id) {
    $count = 0;
    $query = "SELECT SUM(qty) AS total FROM cart_items WHERE cart_id = $cart_id";
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $count = intval($row['total']);
    }
    return $count;
}

$cart_id = getCartId($connection, $user_id, $session_id);

switch ($action) {
    case 'add':
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;
        $color = isset($_POST['color']) ? mysqli_real_escape_string($connection, $_POST['color']) : '';

        if ($id > 0) {
            // Check if item exists
            $check = "SELECT id, qty FROM cart_items WHERE cart_id = $cart_id AND product_id = $id AND color = '$color' LIMIT 1";
            $result = mysqli_query($connection, $check);
            if ($result && mysqli_num_rows($result) > 0) {
                // Update qty
                $row = mysqli_fetch_assoc($result);
                $new_qty = $row['qty'] + $qty;
                $item_id = $row['id'];
                mysqli_query($connection, "UPDATE cart_items SET qty = $new_qty WHERE id = $item_id");
            } else {
                // Insert new item
                mysqli_query($connection, "INSERT INTO cart_items (cart_id, product_id, qty, color) VALUES ($cart_id, $id, $qty, '$color')");
            }
            echo json_encode(['success' => true, 'message' => 'Product added to cart', 'cart_count' => getTotalCount($connection, $cart_id)]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
        }
        break;

    case 'update':
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $color = isset($_POST['color']) ? mysqli_real_escape_string($connection, $_POST['color']) : '';
        $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

        if ($qty < 1) {
             echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
             exit;
        }

        if ($id > 0) {
            mysqli_query($connection, "UPDATE cart_items SET qty = $qty WHERE cart_id = $cart_id AND product_id = $id AND color = '$color'");
            echo json_encode(['success' => true, 'message' => 'Cart updated', 'cart_count' => getTotalCount($connection, $cart_id)]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
        }
        break;

    case 'remove':
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $color = isset($_POST['color']) ? mysqli_real_escape_string($connection, $_POST['color']) : '';

        if ($id > 0) {
            mysqli_query($connection, "DELETE FROM cart_items WHERE cart_id = $cart_id AND product_id = $id AND color = '$color'");
            echo json_encode(['success' => true, 'message' => 'Item removed', 'cart_count' => getTotalCount($connection, $cart_id)]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
