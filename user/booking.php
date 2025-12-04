<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "vehiclerentalsystem");
if ($conn->connect_error) die("Database connection failed");

$username = $_SESSION['username'];

// eSewa config
$secretKey = "8gBm/:&EnhH.1/q"; 
$product_code = "EPAYTEST";
$signed_field_names = "total_amount,transaction_uuid,product_code";

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vehicle_id'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];


    $result = $conn->query("SELECT price_per_day FROM vehicles WHERE vehicle_id = $vehicle_id");
    $row = $result->fetch_assoc();
    $price_per_day = $row['price_per_day'];
    $days = (strtotime($end_date) - strtotime($start_date)) / 86400 + 1;
    $total_amount = $price_per_day * $days;

    // Insert booking
    $conn->query("INSERT INTO booking (user_name, vehicle_id, start_date, end_date, total_amount)
                  VALUES ('$username', '$vehicle_id', '$start_date', '$end_date', '$total_amount')");
    $booking_id = $conn->insert_id;

  // Generate a unique transaction UUID
$transaction_uuid = $booking_id . '-' . uniqid('', true);
$stmt = $conn->prepare("UPDATE booking SET transaction_uuid=? WHERE booking_id=?");
$stmt->bind_param("si", $transaction_uuid, $booking_id);
$stmt->execute();

    header("Location: booking.php");
    exit();
}

// Fetch user's bookings
$query = "SELECT b.*, v.vehicle_name, v.model, v.price_per_day
          FROM booking b
          JOIN vehicles v ON b.vehicle_id = v.vehicle_id
          WHERE b.user_name = '$username'
          ORDER BY b.start_date DESC";
$booking = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <header class="header">
        <div class="left">
            <img src="./img/logo1.png" class="imglogo" alt="Easy Ride Logo">
            <span class="brand">EASY RIDE</span>
        </div>
    </header>

    <div class="flex">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="users-profile">
                <img src="https://t4.ftcdn.net/jpg/16/09/59/37/360_F_1609593795_Ae1PPBgGSiy2tKw4GWXeXJtBTQn3dWpn.jpg"
                    alt="">
            </div>
            <h4>Welcome, <span><?= htmlspecialchars($username) ?></span></h4>
            <a href="./db.php" class="menu"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="./rent.php" class="menu"><i class="fa-regular fa-square-plus"></i> Rent Vehicle</a>
            <a href="./booking.php" class="menu"><i class="fa-solid fa-car-side"></i> Our Booking</a>
            <a href="./logout.php" class="menu"><i class="fa-solid fa-right-from-bracket"></i>Log Out</a>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="main">
            <section id="dashboard">
                <div class="text">
                    <h2>My Booking</h2>
                    <p class="dash">Here is your booking information.</p>
                </div>

                <table width="100%" cellpadding="10">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Vehicle Name</th>
                            <th>Model</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
        if ($booking->num_rows > 0):
            $sn = 1;
            while ($row = $booking->fetch_assoc()):
                $days = (strtotime($row['end_date']) - strtotime($row['start_date'])) / 86400 + 1;
                $total_amount = $row['price_per_day'] * $days;
                $total_amount_str = number_format($total_amount, 2, '.', '');

                $transaction_uuid = $row['transaction_uuid']; // use fixed transaction_uuid
                $signed_fields = [
                    'total_amount' => $total_amount_str,
                    'transaction_uuid' => $transaction_uuid,
                    'product_code' => $product_code
                ];
                $fields_order = explode(',', $signed_field_names);
                $data_to_sign = [];
                foreach ($fields_order as $field) $data_to_sign[] = $field . '=' . $signed_fields[$field];
                $data_string = implode(',', $data_to_sign);
                $hash = hash_hmac('sha256', $data_string, $secretKey, true);
                $signature = base64_encode($hash);
        ?>
            <tr>
                <td><?= $sn ?></td>
                <td><?= htmlspecialchars($row['vehicle_name']) ?></td>
                <td><?= htmlspecialchars($row['model']) ?></td>
                <td><?= $row['start_date'] ?></td>
                <td><?= $row['end_date'] ?></td>
                <td>NPR <?= $total_amount_str ?></td>
                <td>
                    <form method="POST" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form">
                        <input type="hidden" name="amount" value="<?= $total_amount_str ?>">
                        <input type="hidden" name="tax_amount" value="0">
                        <input type="hidden" name="total_amount" value="<?= $total_amount_str ?>">
                        <input type="hidden" name="transaction_uuid" value="<?= $transaction_uuid ?>">
                        <input type="hidden" name="product_code" value="<?= $product_code ?>">
                        <input type="hidden" name="product_service_charge" value="0">
                        <input type="hidden" name="product_delivery_charge" value="0">
                        <input type="hidden" name="success_url" value="http://localhost/vehiclerentalsystem/success.php">
                        <input type="hidden" name="failure_url" value="http://localhost/vehiclerentalsystem/failure.php">
                        <input type="hidden" name="signed_field_names" value="<?= $signed_field_names ?>">
                        <input type="hidden" name="signature" value="<?= $signature ?>">
                        <input type="submit" value="Pay with eSewa">
                        <td><?= htmlspecialchars($row['payment_status'] ?? 'UnPaid') ?></td>

                    </form>
                </td>
            </tr>
        <?php
                $sn++;
            endwhile;
        else:
            echo "<tr><td colspan='7'>No bookings found</td></tr>";
        endif;
        ?>
        </tbody>
    </table>
</div>
</div>
</body>
</html>