<?php
//$conn = new mysqli('localhost', 'root', '', 'rch_db');
$conn = new mysqli('sql.freedb.tech', 'freedb_etheria2024', 'EXH$fvdNh78zv*J', 'freedb_rch_db');

$product_id = intval($_POST['product_id']);

$query = "UPDATE productstbl SET quantity = quantity - 1 WHERE product_id = ? AND quantity > 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
?>
