<?php
//$conn = new mysqli('localhost', 'root', '', 'rch_db');
$conn = new mysqli('sql.freedb.tech', 'freedb_etheria2024', 'EXH$fvdNh78zv*J', 'freedb_rch_db');

$product_id = intval($_POST['product_id']);

$query = "SELECT quantity FROM productstbl WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($quantity);
$stmt->fetch();
$stmt->close();

if ($quantity > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Product is out of stock.']);
}
?>
