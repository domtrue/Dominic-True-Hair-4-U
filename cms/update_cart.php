<?php
session_start(); // Start the session

$response = array('success' => false, 'message' => 'Unknown error', 'newTotal' => 0);

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $key => $quantity) {
            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['quantity'] = max(1, intval($quantity));
            }
        }
    }

    if (isset($_POST['remove_item'])) {
        $itemKey = intval($_POST['remove_item']);
        if (isset($_SESSION['cart'][$itemKey])) {
            unset($_SESSION['cart'][$itemKey]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }

    $_SESSION['order_total'] = array_reduce($_SESSION['cart'], function ($total, $item) {
        return $total + ($item['price'] * $item['quantity']);
    }, 0);

    $response['success'] = true;
    $response['message'] = 'Cart updated successfully';
    $response['newTotal'] = $_SESSION['order_total'];
} else {
    $response['message'] = 'Cart is empty or not initialized';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
