<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <link rel="stylesheet" href="db.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <div class="left">
        <img src="./img/logo1.png" class="imglogo" alt="Easy Ride Logo">
            <span class="brand">EASY RIDE</span>
        </div>
    </header>

    <div class="flex">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="users-profile">
                <img src="https://t4.ftcdn.net/jpg/16/09/59/37/360_F_1609593795_Ae1PPBgGSiy2tKw4GWXeXJtBTQn3dWpn.jpg"
                    alt="Profile">
            </div>

               <h4>Welcome, <?php echo $_SESSION['username']; ?></h4>
   
            <a href="./db.php" class="menu"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="./rent.php" class="menu"><i class="fa-regular fa-square-plus"></i> Rent Vehicle</a>
            <a href="./booking.php" class="menu"><i class="fa-solid fa-car-side"></i> Our Booking</a>
            <a href="./logout.php" class="menu"><i class="fa-solid fa-right-from-bracket"></i>Log Out</a>
        </div>

        <!-- MAIN DASHBOARD CONTENT -->
        <div class="main">
            <section id="dashboard">

                <div class="text">
                    <h2>User Dashboard</h2>
                    <p class="dash">Rent your perfect vehicle easily,car ,bike,or scooter. Check availability,compare prices,and book your ride in  <br> just a few clicks!
                       </p>
                </div>

                <!-- STAT BOXES -->
                <div class="flex">

                    <div class="box">
                        <div class="flex">
                            <div class="boxtext">
                                <h4>Total Vehicle</h4>
                            </div>
                            <div class="round"><i class="fa-solid fa-car-side"></i></div>
                        </div>
                        <p>1</p>
                    </div>

                    <div class="box">
                        <div class="flex">
                            <div class="boxtext">
                                <h4>Total Booking</h4>
                            </div>
                            <div class="round"><i class="fa-solid fa-clipboard-list"></i></div>
                        </div>
                        <p>1</p>
                    </div>

                    <div class="box">
                        <div class="flex">
                            <div class="boxtext">
                                <h4>Pending</h4>
                            </div>
                            <div class="round"><i class="fa-solid fa-alarm-clock"></i></div>
                        </div>
                        <p>1</p>
                    </div>

                    <div class="box">
                        <div class="flex">
                            <div class="boxtext">
                                <h4>Confirmed</h4>
                            </div>
                            <div class="round"><i class="fa-solid fa-clipboard-check"></i></div>
                        </div>
                        <p>1</p>
                    </div>

                </div>

      <!-- VEHICLE CATEGORIES -->
    <section class="section categories">
        <h2>Trading Vehicles</h2>
        <div class="cards">
            <div class="card">
                <img src="./img/tyoyota.png" alt="Scooter">
                <h3>Toyota Land Cruiser 200</h3>
              <a href=""><button>Rent Now</button></a> 
            </div>
            <div class="card">
                <img src="./img/bike1.png" alt="Bike">
                <h3>FZS FI V2</h3>
                <a href=""><button>Rent Now</button></a> 
            </div>
            <div class="card">
                <img src="./img/scooter1.png" alt="Car">
                <h3>Honda Grazia</h3>
                <a href=""><button>Rent Now</button></a> 
            </div>
        </div>
    </section>
</div>
            </section>
        </div>
    </div>

</body>

</html>