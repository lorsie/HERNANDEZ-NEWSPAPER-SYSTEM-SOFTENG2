<style>
  /* Navbar background */
  .navbar {
    background-color: #694429ff !important; /* Hello Kitty pink */
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
  }

  /* Navbar brand */
  .navbar .navbar-brand {
    color: #fff !important;
    font-weight: bold;
  }

  /* All nav links styled as buttons */
  .navbar .nav-link {
    color: #fff !important;              
    font-weight: 600;
    padding: 8px 18px;
    margin: 0 5px;
    border-radius: 25px;                 
    transition: 0.3s;
    background-color: #efd442; 
  }

  /* Hover effect */
  .navbar .nav-link:hover {
    background-color: #906e57ff !important; 
    color: #fff !important;
  }
</style>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #ff4d88;">
  <a class="navbar-brand text-white font-weight-bold" href="index.php">
    🌸 Admin Panel 🌸
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="articles_from_students.php">Pending Articles</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="articles_submitted.php">Articles Submitted</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a>
      </li>
    </ul>
  </div>
</nav>
