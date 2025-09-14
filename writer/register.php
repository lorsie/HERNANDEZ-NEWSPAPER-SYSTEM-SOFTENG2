<?php require_once 'classloader.php'; ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Writer Registration</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: url("https://i.pinimg.com/736x/ba/f6/c5/baf6c5d2c5ab5f7ff4003b9d8e4a5dc7.jpg") no-repeat center center fixed;
      background-size: cover;
    }
    .register-card {
      max-width: 600px;
      margin: 80px auto;
      border-radius: 15px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.2);
      overflow: hidden;
      background: rgba(255, 255, 255, 0.95); /* semi-transparent white */
    }
    .register-card-header {
      background: #965f37;
      color: #fff;
      padding: 20px;
      text-align: center;
    }
    .register-card-header h2 {
      margin: 0;
      font-size: 1.6rem;
      font-weight: 600;
    }
    .btn-custom {
      background: #965f37;
      color: #fff;
      border: none;
      transition: 0.3s;
    }
    .btn-custom:hover {
      background: #965f37;
    }
    a {
      color: #965f37;
      font-weight: 600;
    }
    a:hover {
      color: #965f37;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="register-card card">
      <div class="register-card-header">
        <h2>Writer Registration</h2>
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
          }
          unset($_SESSION['message']);
          unset($_SESSION['status']);
          ?>
          
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
          </div>
          
          <button type="submit" class="btn btn-custom btn-block mt-3" name="insertNewUserBtn">Register as Writer</button>
          
          <p class="mt-3 text-center">
            Already have an account? <a href="login.php">Login here</a>
          </p>
        </div>
      </form>
    </div>
  </div>
</body>
</html>