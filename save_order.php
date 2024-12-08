<?php

//$conn = new mysqli('localhost', 'root', '', 'rch_db');
$conn = new mysqli('sql.freedb.tech', 'freedb_etheria2024', 'EXH$fvdNh78zv*J', 'freedb_rch_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orderDetails = json_decode($_POST['orderDetails'], true);
$total = $orderDetails['total'];
$cashGiven = $orderDetails['cashGiven'];
$change = $orderDetails['change'];

// Insert order into the database
$insertOrderQuery = "INSERT INTO orderstbl (total_price, cash_given, change, date_created) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($insertOrderQuery);
$stmt->bind_param("ddd", $total, $cashGiven, $change);
if ($stmt->execute()) {
    $orderId = $stmt->insert_id; // Get the ID of the newly inserted order
    // Insert the products for this order
    foreach ($orderDetails['products'] as $prod) {
        $insertProductQuery = "INSERT INTO order_details (order_id, product_name, price, quantity, total_price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertProductQuery);
        $stmt->bind_param("isddi", $orderId, $prod['name'], $prod['price'], $prod['quantity'], $prod['totalPrice']);
        $stmt->execute();
    }
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}

$stmt->close();
$conn->close();


?>
