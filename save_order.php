<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //$conn = new mysqli('localhost', 'root', '', 'rch_db');
    $conn = new mysqli('sql.freedb.tech', 'freedb_etheria2024', 'EXH$fvdNh78zv*J', 'freedb_rch_db');

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
        exit;
    }

    $orderDetails = json_decode($_POST['orderDetails'], true);
    $cash_given = $_POST['cash_given'];
    $change = $_POST['change'];
    $date_created = date('Y-m-d H:i:s');

    if (!$orderDetails) {
        echo json_encode(['success' => false, 'message' => 'Invalid order details']);
        exit;
    }

    try {
        foreach ($orderDetails as $order) {
            $sql = "INSERT INTO orderstbl (product_name, price, quantity, total_price, cash_given, `change`, date_created) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param(
                'sdiidss',
                $order['product_name'],
                $order['price'],
                $order['quantity'],
                $order['total_price'],
                $cash_given,
                $change,
                $date_created
            );

            if (!$stmt->execute()) {
                throw new Exception("Execution failed: " . $stmt->error);
            }
        }
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        $conn->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>