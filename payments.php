<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bill_type = $_POST['bill_type'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO payments (user_id, bill_type, amount, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $bill_type, $amount, $payment_method);

    if ($stmt->execute()) {
        echo "Payment submitted successfully.";
    } else {
        echo "Payment submission failed.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Payments</title>
    <link rel="stylesheet" href="source/css/payments.css">
    <link rel="stylesheet" href="source/css/main.css">

</head>
<body>
    <div class="payment-container">
        <h2>Bill Payment</h2>
        <form action="payments.php" method="post">
            <label for="bill_type">Bill Type</label>
            <select name="bill_type" id="bill_type">
                <option value="utility">Utility Bill</option>
                <option value="property">Property Tax</option>
            </select>

            <label for="amount">Amount</label>
            <input type="number" id="amount" name="amount" step="0.01" min="0" required>

            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method">
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
            </select>

            <button type="submit">Pay Now</button>
        </form>
    </div>
</body>
</html>
