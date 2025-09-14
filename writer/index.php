<?php require_once 'classloader.php'; ?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if ($userObj->isAdmin()) {
  header("Location: ../admin/index.php");
}  
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
  background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
              url("https://i.pinimg.com/736x/ba/f6/c5/baf6c5d2c5ab5f7ff4003b9d8e4a5dc7.jpg");
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  color: #333;
}

/* Welcome box */
.welcome-box {
  background: rgba(255, 255, 255, 0.9);
  border-radius: 16px;
  padding: 25px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.25);
  margin: 30px auto;
  text-align: center;
  max-width: 900px;
}

.welcome-box h1 {
  font-size: 1.9rem;
  font-weight: 700;
  color: #965f37; 
  text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
}

/* Form box */
.form-box {
  background: rgba(255, 255, 255, 0.95);
  padding: 20px;
  border-radius: 14px;
  box-shadow: 0 3px 12px rgba(0,0,0,0.15);
  margin-bottom: 30px;
}

/* Submit button */
.btn-danger {
  background-color: #965f37 !important; 
  border: none;
  font-weight: 600;
  border-radius: 8px;
}

.btn-danger:hover {
  background-color: #965f37 !important;
}

/* Article cards */
.card.article-card {
  border-radius: 14px;
  background: rgba(255, 255, 255, 0.95);
  box-shadow: 0 3px 12px rgba(0,0,0,0.15);
  transition: transform 0.2s ease;
}

.card.article-card:hover {
  transform: translateY(-4px);
}

.card.article-card h4 {
  font-weight: 700;
  color: #333;
}

/* Admin badge */
.admin-badge {
  background-color: #965f37; 
  color: #fff;
  font-size: 0.8rem;
  border-radius: 8px;
  padding: 3px 10px;
}

/* Request Edit button */
.btn-outline-primary {
  border-color: #965f37;
  color: #965f37;
  font-weight: 600;
  border-radius: 8px;
}

.btn-outline-primary:hover {
  background-color: #2965f37;
  color: #fff;
}
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="welcome-box">
      <h1>Hello there and welcome, 
        <span style="color: #efd442;"><?php echo $_SESSION['username']; ?></span>
      </h1>
      <p class="text-muted mb-0">Here are all the articles:</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-8">
        
        <!-- Article Form -->
        <div class="form-box">
          <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <input type="text" class="form-control" name="title" placeholder="Enter article title" required>
            </div>
            <div class="form-group">
              <textarea name="description" class="form-control" rows="4" placeholder="Write your article..." required></textarea>
            </div>
            <div class="form-group">
              <label for="image">Article Image (Optional)</label>
              <input type="file" class="form-control-file" name="image" id="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-danger btn-block" name="insertArticleBtn">
              Submit Article
            </button>
          </form>
        </div>

        <!-- Articles -->
        <?php $articles = $articleObj->getActiveArticles(); ?>
        <?php foreach ($articles as $article) { ?>
        <div class="card article-card mb-4">
          <div class="card-body">
            <h4><?php echo $article['title']; ?></h4> 

            <?php if ($article['is_admin'] == 1) { ?>
              <p><span class="badge badge-primary admin-badge">Message From Admin</span></p>
            <?php } ?>

            <small class="text-muted d-block mb-2">
              <strong><?php echo $article['username']; ?></strong> · <?php echo $article['created_at']; ?>
            </small>

            <?php if (!empty($article['image_path'])) { ?>
              <div class="mb-3">
                <img src="../<?php echo $article['image_path']; ?>" class="img-fluid rounded" alt="Article Image" style="max-height: 300px;">
              </div>
            <?php } ?>

            <p><?php echo $article['content']; ?></p>
            
            <?php if ($article['author_id'] != $_SESSION['user_id']) { ?>
              <div class="mt-3">
                <form action="core/handleForms.php" method="POST" style="display: inline;">
                  <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                  <button type="submit" class="btn btn-sm btn-outline-primary" name="requestEditBtn">
                    Request Edit Access
                  </button>
                </form>
              </div>
            <?php } ?>
          </div>
        </div>  
        <?php } ?> 

      </div>
    </div>
  </div>
</body>
</html>