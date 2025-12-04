<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "vehiclerentalsystem");
if ($conn->connect_error) die("Connection Failed: " . $conn->connect_error);

$email = $_SESSION['email'];
$res = $conn->query("SELECT username FROM users WHERE email='$email'");
$username = ($res->num_rows > 0) ? $res->fetch_assoc()['username'] : "User";

$vehicles = $conn->query("SELECT * FROM vehicles WHERE status='available'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Dashboard</title>

    <link rel="stylesheet" href="header.css"> 
    <link rel="stylesheet" href="rent.css">

    <link rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<body>

    <!-- USE YOUR EXISTING HEADER -->
    <header class="header">
        <div class="left">
            <img src="./img/logo1.png" class="imglogo" alt="Easy Ride Logo">
            <span class="brand">EASY RIDE</span>
        </div>
    </header>

    <div class="layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="users-profile">
                <img src="https://t4.ftcdn.net/jpg/16/09/59/37/360_F_1609593795_Ae1PPBgGSiy2tKw4GWXeXJtBTQn3dWpn.jpg" alt="">
            </div>

            <h4>Welcome, <span><?php echo $username; ?></span></h4>

            <a href="./db.php" class="menu"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="./rent.php" class="menu"><i class="fa-regular fa-square-plus"></i> Rent Vehicle</a>
            <a href="./booking.php" class="menu"><i class="fa-solid fa-car-side"></i> Our Booking</a>
            <a href="./logout.php" class="menu"><i class="fa-solid fa-right-from-bracket"></i>Log Out</a>
        </aside>

        <!-- MAIN SECTION -->
        <main class="main">

            <div class="text">
                <h2>Rent Now</h2>
                <p class="dash">

Easily book your favorite vehicle anytime and enjoy a smooth, hassle-free ride experience.</p>
            </div>

            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search vehicles...">
              
            </div>

            <div class="vehicle-list">
                <?php
                if ($vehicles->num_rows > 0) {
                    while ($v = $vehicles->fetch_assoc()) {
                        $id = $v['vehicle_id'];
                        $name = $v['vehicle_name'];
                        $type = $v['category_type'];
                        $model = $v['model'];
                        $price = $v['price_per_day'];
                        $img = $v['image'] ?: 'placeholder.png';

                        echo "
                        <div class='vehicle-card'>
                            <img src='$img' alt='$name'>
                            <h3>$name</h3>
                            <p>Type: $type</p>
                            <p>Model: $model</p>
                            <p>Price/day: Rs. <span>$price</span></p>
                            <button onclick='openForm($id, $price)'>Book Now</button>
                        </div>";
                    }
                } else {
                    echo "<p>No vehicles available.</p>";
                }
                ?>
            </div>

        </main>
    </div>

    <!-- POPUP BOOKING FORM -->
    <div id="bookingForm">
        <div class="form-container">
            <h3>Book Vehicle</h3>

            <form action="booking.php" method="post">
                <input type="hidden" name="vehicle_id" id="vehicle_id"><br>

                <label>Start Date:</label>
                <input type="date" id="start_date" name="start_date" required onchange="calculateTotal()"><br>

                <label>End Date:</label>
                <input type="date" id="end_date" name="end_date" required onchange="calculateTotal()">

                <p>Days: <span id="num_days_display">0</span></p>
                <p>Total Price: Rs. <span id="total_price">0</span></p>

                <input type="hidden" id="num_days_input" name="num_days">
                <input type="hidden" id="total_price_input" name="total_price">

                <button type="submit">Confirm Booking</button><br>
                <button type="button" class="close-btn" onclick="closeForm()">Cancel</button>
            </form>
        </div>
    </div>

    <script src="rent.js"></script>

</body>
</html>
