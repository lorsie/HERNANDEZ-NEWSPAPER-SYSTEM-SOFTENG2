<?php require_once 'classloader.php'; ?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isAdmin()) {
  header("Location: ../writer/index.php");
}  
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Dashboard</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
              url("https://i.pinimg.com/1200x/d4/c6/d6/d4c6d6bc17db916974f9762c0e4d8244.jpg");
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
  color: #333;
    }

    .welcome-box {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.2);
      margin: 30px auto;
      text-align: center;
      max-width: 900px;
    }

    .welcome-box h2 {
      font-size: 1.9rem;
      font-weight: 600;
      color: #965f37; 
    }

    .welcome-box p {
      font-size: 1.1rem;
      font-weight: 400;
      color: #555;
    }

    .form-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 12px rgba(0,0,0,0.15);
      margin-bottom: 30px;
    }

    .article-card {
      border: none;
      border-radius: 15px;
      background: rgba(255, 255, 255, 0.95);
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-custom {
      background: #965f37;
      color: #fff;
      border: none;
      border-radius: 30px;
      transition: 0.3s;
    }

    .btn-custom:hover {
      background: #965f37;
      transform: scale(1.05);
    }

    .form-control, 
    .form-control-file, 
    textarea {
      border-radius: 12px;
    }

    .badge-danger {
      background: #965f37 !important;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container mt-5">
    <!-- Welcome -->
    <div class="welcome-box">
      <h2>Welcome, <span style="color: #efd442;"><?php echo $_SESSION['username']; ?></span>
      <p>Manage articles from the admin side</p>
    </div>

    <!-- Form -->
    <div class="form-box">
      <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <input type="text" class="form-control" name="title" placeholder="Article title" required>
        </div>
        <div class="form-group">
          <textarea name="description" class="form-control" placeholder="Message as admin" rows="4" required></textarea>
        </div>
        <div class="form-group">
          <label for="image">Article Image (Optional)</label>
          <input type="file" class="form-control-file" name="image" id="image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-custom btn-block" name="insertAdminArticleBtn">Post Article</button>
      </form>
    </div>

    <!-- Articles -->
    <?php $articles = $articleObj->getActiveArticles(); ?>
    <?php foreach ($articles as $article) { ?>
      <div class="article-card">
        <h4><?php echo $article['title']; ?></h4>
        <?php if ($article['is_admin'] == 1) { ?>
          <p><span class="badge badge-danger">Admin Message</span></p>
        <?php } ?>
        <small class="text-muted">
          <strong><?php echo $article['username']; ?></strong> • <?php echo $article['created_at']; ?>
        </small>
        
        <?php if (!empty($article['image_path'])) { ?>
          <div class="mb-3">
            <img src="../<?php echo $article['image_path']; ?>" class="img-fluid rounded" alt="Article Image" style="max-height: 300px;">
          </div>
        <?php } ?>
        
        <p class="mt-2"><?php echo $article['content']; ?></p>
      </div>
    <?php } ?>
  </div>
</body>
</html>