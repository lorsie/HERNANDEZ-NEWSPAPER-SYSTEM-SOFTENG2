<?php require_once 'classloader.php'; ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <style>
      body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
          url("https://i.pinimg.com/736x/ba/f6/c5/baf6c5d2c5ab5f7ff4003b9d8e4a5dc7.jpg");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
      }

      .card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      }

      .card-header {
        background-color: #965f37; 
        color: #fff;
        border-radius: 16px 16px 0 0;
        text-align: center;
        font-weight: 700;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
      }

      .btn-primary {
        background-color: #965f37;
        border: none;
        border-radius: 8px;
        font-weight: 600;
      }

      .btn-primary:hover {
        background-color: #965f37;
      }

      a {
        color: #965f37;
        font-weight: 600;
      }

      a:hover {
        color: #965f37;
        text-decoration: none;
      }

      h1 {
        font-size: 1.1rem;
      }
    </style>

    <title>Writer Login</title>
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 p-5">
          <div class="card shadow">
            <div class="card-header">
              🌸 Welcome to Writer’s Dashboard — Login Now!🌸 
            </div>
            <form action="core/handleForms.php" method="POST">
              <div class="card-body">
                <?php  
                if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                  if ($_SESSION['status'] == "200") {
                    echo "<h1 style='color: #965f37;'>{$_SESSION['message']}</h1>";
                  } else {
                    echo "<h1 style='color: #965f37;'>{$_SESSION['message']}</h1>"; 
                  }
                }
                unset($_SESSION['message']);
                unset($_SESSION['status']);
                ?>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control" name="email" required>
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" name="password" required>
                  <input type="submit" class="btn btn-primary float-right mt-4" name="loginUserBtn" value="Login">
                </div>
                <p class="mt-3">Don’t have an account yet?  
                  <a href="register.php">Register here!</a>
                </p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>