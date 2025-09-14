<?php require_once 'writer/classloader.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: #f8f9fa;
    }

    /* Navbar */
    .navbar {
      background: linear-gradient(90deg, #efd442, #965f37);
    }
    .navbar-brand {
      color: #361701ff !important;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
    }

    /* Hero */
    .hero {
      padding: 4rem 1rem;
      text-align: center;
      background: white;
      border-bottom: 1px solid #eee;
    }
    .hero h1 {
      font-size: 2.5rem;
      font-weight: bold;
      color: #965F37;
      text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
    }
    .hero p {
      font-size: 1.2rem;
      color: #555;
      text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
    }

    /* Section Title */
    .section-title {
      font-size: 2rem;
      font-weight: bold;
      margin: 2rem 0 1rem;
      text-align: center;
      color: #965F37;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
    }

    /* Cards */
    .card {
      border: 2px solid #965F37;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.2s;
      background: rgba(255,255,255,0.95);
    }
    .card:hover {
      transform: translateY(-4px);
    }
    .card-title {
      font-weight: bold;
    }

    /* Writer Card */
    .writer-card {
      background: url("https://i.pinimg.com/736x/ba/f6/c5/baf6c5d2c5ab5f7ff4003b9d8e4a5dc7.jpg") no-repeat center/cover;
      color: #6f3409ff;
      position: relative;
    }
    .writer-card h4, 
    .writer-card p, 
    .writer-card .btn {
      text-shadow: 1px 1px 4px rgba(0,0,0,0.8);
    }
    .writer-card .btn {
      background-color: #965F37;
      border: none;
      color: #fff;
    }
    .writer-card .btn:hover {
      background-color: #965F37;
    }

    /* Admin Card */
    .admin-card {
      background: url("https://i.pinimg.com/1200x/d4/c6/d6/d4c6d6bc17db916974f9762c0e4d8244.jpg") no-repeat center/cover;
      color: #6f3409ff;
      position: relative;
    }
    .admin-card h4, 
    .admin-card p, 
    .admin-card .btn {
      text-shadow: 1px 1px 4px rgba(0,0,0,0.8);
    }
    .admin-card .btn {
      background-color: #965F37;
      border: none;
      color: #fff;
    }
    .admin-card .btn:hover {
      background-color: #965F37;
    }

    /* Article Cards */
    .article-card h4 {
      color: #28a745;
    }
    .badge-primary {
      background-color: #ff69b4 !important;
    }
  </style>
</head>
<body>
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark p-3">
    <a class="navbar-brand font-weight-bold" href="#">School Publication</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" 
            data-target="#navbarNav" aria-controls="navbarNav" 
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>

  <!-- HERO -->
  <div class="hero">
    <h1>Welcome to the School Publication</h1>
    <p class="lead">Your hub for articles, writers, and admin updates.</p>
  </div>

  <!-- WRITER / ADMIN SECTIONS -->
  <div class="container my-5">
    <div class="row">
      <!-- Writer -->
      <div class="col-md-6 mb-4">
        <div class="card h-100 writer-card d-flex align-items-center justify-content-center text-center">
          <div class="card-body">
            <h4 class="card-title">Writer</h4>
            <p class="card-text">Writers create engaging and informative content that builds authority and attracts readers.</p>
            <a href="writer/login.php" class="btn">Login as Writer</a>
          </div>
        </div>
      </div>

      <!-- Admin -->
      <div class="col-md-6 mb-4">
        <div class="card h-100 admin-card d-flex align-items-center justify-content-center text-center">
          <div class="card-body">
            <h4 class="card-title">Admin</h4>
            <p class="card-text">Admins manage the editorial process, ensuring quality and alignment with the publication’s vision.</p>
            <a href="admin/login.php" class="btn">Login as Admin</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ARTICLES -->
  <div class="container mb-5">
    <h2 class="section-title">Latest Articles</h2>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <?php $articles = $articleObj->getActiveArticles(); ?>
        <?php foreach ($articles as $article) { ?>
          <div class="card article-card mb-4">
            <div class="card-body">
              <h4 class="card-title"><?php echo $article['title']; ?></h4>
              <?php if ($article['is_admin'] == 1) { ?>
                <span class="badge badge-primary mb-2">Message from Admin</span>
              <?php } ?>
              <p class="text-muted small">
                <strong><?php echo $article['username']; ?></strong> • 
                <?php echo $article['created_at']; ?>
              </p>
              <?php if (!empty($article['image_path'])) { ?>
                <img src="<?php echo $article['image_path']; ?>" 
                     class="img-fluid rounded mb-3" alt="Article Image" style="max-height: 300px; object-fit:cover;">
              <?php } ?>
              <p><?php echo $article['content']; ?></p>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</body>
</html>