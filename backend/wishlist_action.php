<?php
session_start();
require_once '../config/db_config.php';
header('Content-Type: application/json');

// Must be logged in
if (empty($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Please log in to manage your wishlist', 'login_required' => true]);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$action  = isset($_POST['action']) ? $_POST['action'] : '';

// Helper: count wishlist items
function getWishlistCount($connection, $user_id) {
    $query  = "SELECT COUNT(*) AS total FROM wishlist WHERE user_id = $user_id";
    $result = mysqli_query($connection, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return intval($row['total']);
    }
    return 0;
}

switch ($action) {

    /* ── Toggle (add if missing, remove if present) ── */
    case 'toggle':
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        if ($product_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
            exit;
        }

        // Check if already in wishlist
        $check = mysqli_query($connection, "SELECT id FROM wishlist WHERE user_id = $user_id AND product_id = $product_id LIMIT 1");
        if ($check && mysqli_num_rows($check) > 0) {
            // Remove
            mysqli_query($connection, "DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id");
            echo json_encode([
                'success'        => true,
                'action'         => 'removed',
                'message'        => 'Removed from wishlist',
                'wishlist_count' => getWishlistCount($connection, $user_id)
            ]);
        } else {
            // Add
            mysqli_query($connection, "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)");
            echo json_encode([
                'success'        => true,
                'action'         => 'added',
                'message'        => 'Added to wishlist',
                'wishlist_count' => getWishlistCount($connection, $user_id)
            ]);
        }
        break;

    /* ── Remove ── */
    case 'remove':
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        if ($product_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid product']);
            exit;
        }
        mysqli_query($connection, "DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id");
        echo json_encode([
            'success'        => true,
            'message'        => 'Removed from wishlist',
            'wishlist_count' => getWishlistCount($connection, $user_id)
        ]);
        break;

    /* ── List all wishlist items ── */
    case 'list':
        $query = "SELECT w.id, w.product_id, w.created_at,
                         p.product_name, p.image, p.price, p.original_price, p.stock
                  FROM wishlist w
                  JOIN products p ON w.product_id = p.id
                  WHERE w.user_id = $user_id
                  ORDER BY w.created_at DESC";
        $result = mysqli_query($connection, $query);
        $items  = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = $row;
            }
        }
        echo json_encode(['success' => true, 'items' => $items, 'wishlist_count' => count($items)]);
        break;

    /* ── Check if a product is wishlisted ── */
    case 'check':
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $check = mysqli_query($connection, "SELECT id FROM wishlist WHERE user_id = $user_id AND product_id = $product_id LIMIT 1");
        $in_wishlist = ($check && mysqli_num_rows($check) > 0);
        echo json_encode(['success' => true, 'in_wishlist' => $in_wishlist]);
        break;

    /* ── Bulk check (for shop/index pages) ── */
    case 'check_bulk':
        $ids_raw = isset($_POST['product_ids']) ? $_POST['product_ids'] : '';
        $ids     = array_filter(array_map('intval', explode(',', $ids_raw)));
        $wishlisted = [];
        if (!empty($ids)) {
            $id_list = implode(',', $ids);
            $result  = mysqli_query($connection, "SELECT product_id FROM wishlist WHERE user_id = $user_id AND product_id IN ($id_list)");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $wishlisted[] = intval($row['product_id']);
                }
            }
        }
        echo json_encode(['success' => true, 'wishlisted' => $wishlisted]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
