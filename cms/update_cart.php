<?php
session_start(); // Start the session

// Ensure there is a cart
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty']);
    exit();
}

// Process form submissions for updating quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemTotals = []; // Array to store individual item totals

    if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $key => $quantity) {
            if (isset($_SESSION['cart'][$key])) {
                $quantity = intval($quantity); // Ensure quantity is an integer
                if ($quantity < 1) $quantity = 1; // Enforce minimum quantity of 1
                $_SESSION['cart'][$key]['quantity'] = $quantity;
                
                // Recalculate individual item total
                $itemTotal = $_SESSION['cart'][$key]['price'] * $quantity;
                $itemTotals[$key] = $itemTotal;
            }
        }

        // Recalculate order total
        $orderTotal = array_sum($itemTotals);
        $_SESSION['order_total'] = $orderTotal;

        echo json_encode([
            'success' => true,
            'newTotal' => $orderTotal,
            'itemTotals' => $itemTotals // Send item totals back to client
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid quantity data']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
