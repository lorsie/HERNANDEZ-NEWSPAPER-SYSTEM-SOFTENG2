<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: url("https://i.pinimg.com/1200x/d4/c6/d6/d4c6d6bc17db916974f9762c0e4d8244.jpg") no-repeat center center fixed;
      background-size: cover;
    }

    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.25);
      background: rgba(255, 255, 255, 0.9);
    }

    .card-header {
      background: #965f37;
      color: #fff;
      border-radius: 15px 15px 0 0;
      text-align: center;
      font-weight: bold;
      font-size: 1.4rem;
    }

    .btn-custom {
      background: #965f37;
      color: #fff;
      border: none;
      border-radius: 30px;
      transition: 0.3s;
    }

    .btn-custom:hover {
      background: #9A3F3F;
      transform: scale(1.05);
    }

    .form-control {
      border-radius: 30px;
      padding: 10px 15px;
    }

    .form-group label {
      font-weight: 500;
      color: #333;
    }

    a {
      color: #965f37;
      font-weight: 600;
    }

    a:hover {
      color: #ffffffff;
      text-decoration: ;
    }
  </style>

  <title>Admin Login</title>
</head>
<body>
  <div class="container login-container">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          Admin Panel – Login
        </div>
        <form action="core/handleForms.php" method="POST">
          <div class="card-body">
            <?php  
              if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                if ($_SESSION['status'] == "200") {
                  echo "<div class='alert alert-success'>{$_SESSION['message']}</div>";
                } else {
                  echo "<div class='alert alert-danger'>{$_SESSION['message']}</div>"; 
                }
                unset($_SESSION['message']);
                unset($_SESSION['status']);
              }
            ?>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-custom btn-block mt-3" name="loginUserBtn">Login</button>

            <p class="text-center mt-3 mb-0">
              Don't have an account yet? <a href="register.php">Register here</a>
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
