<?php
session_start();
$conn = new mysqli("localhost", "root", "", "user_db");
if ($conn->connect_error) die("Connection Failed: " . $conn->connect_error);

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM user WHERE email='$email'");
    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $email;
            header("Location: home.html");
            exit;
        } else {
            echo "<script>alert('Invalid password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];

    $check = $conn->query("SELECT * FROM user WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo "<p style='color:red;'>Email already exists.</p>";
    } else {
        $conn->query("INSERT INTO user (name, email, password, phone, address, dob, gender)
                      VALUES ('$name', '$email', '$password', '$phone', '$address', '$dob', '$gender')");
        echo "<p style='color:green;'>Registered successfully. You can login now.</p>";
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM user WHERE email='$email'");
    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $email;
            header("Location: index.php");
            exit;
        } else {
            echo "<p style='color:red;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color:red;'>User not found.</p>";
    }
}
?><!DOCTYPE html>
<html>
<head>
  <title>Login Page</title>
  <style>
    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poopins",serif;
    }
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      
      background: linear-gradient(to right,#e2e2e2,#c9c9c9);
     color: #333;
    }
.container{
  margin: 0 15px;

}
.form-box{
  width: 100%;
  max-width: 450px;
  padding:30px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 0 10px black;
  display: none;
}
.form-box.active{
display: block;
}
h2{
  font-size: 34px;
  text-align: center;
  margin-bottom: 20px;
}
input{
  width: 100%;
  padding: 12px;
  background: #eee;
  border-radius: 6px;
  border: none;
  outline: none;
  font-size: 16px;
  color: #333;
  margin-bottom: 20px;
}
input[type="radio"] {
  width: auto;  
  margin-bottom: 0; 
  margin-right: 5px; 
}

    img {
      width: 50%;
      margin: 20px auto;
      display: block;
      border-radius: 10px;
    }
    p {
      font-size: 14px;
      margin: 10px 0;
    }

    p a{
      color: #007bff;
      text-decoration: none;
    }
    p a:hover {
      text-decoration: underline;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
 <div class="container">
  <div class="form-box active"  id="login-form">
    <form action="" method="post">
      <img src="logo.png" alt="">
      <h2>Login</h2>
      <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
      <p>Don't have an account?<a href="#" onclick="showForm('register-form')">Register</a></p>
    </form>
  </div>
   <div class="form-box" id="register-form">
    <form action="" method="post">
      <h2>Register</h2>
      <input type="name" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
     <input type="password" name="password" placeholder="Password" required>
    <input type="tel" pattern="[0-9]{10}" name=" phone" placeholder="Phone number" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="date" name="dob"  required>
    <label for="gender">Select your Gender :</label>
<input type="radio" id="male" name="gender" value="male">
<label for="male">Male</label>


<input type="radio" id="female" name="gender" value="female">
<label for="female">Female</label>

<input type="radio" id="others" name="gender" value="others">
<label for="others">Others</label>


    <div>
 
     <button type="submit" name="register">Register</button>
      <p>Already have an account?<a href="#" onclick="showForm('login-form')">Login</a></p>
    </form>
  </div>
 </div>
   <script >function showForm(formId){
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}
  </script>
</body>
</html>