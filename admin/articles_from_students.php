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
  <title>Admin - Manage Articles</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
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
    .page-header {
      text-align: center;
      margin-bottom: 30px;
    }
    .page-header h2 {
      font-weight: 600;
      color: #ffffffff;
    }
    .form-box {
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      margin-bottom: 30px;
    }
    .article-card {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }
    .status-badge {
      font-size: 0.85rem;
      padding: 5px 10px;
      border-radius: 5px;
    }
    .status-pending {
      background: #fff9b0;
      color: #965f37;
    }
    .status-active {
      background: #d4edda;
      color: #155724;
    }
    .btn-custom {
      background:#fff9b0;
      color: #fff9b0;
      border: none;
    }
    .btn-custom:hover {
      background: #965f37;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container mt-4">
    <!-- Page Header -->
    <div class="page-header">
      <h2>Manage Articles</h2>
      <p class="text-muted">Add, approve, or remove articles from the system</p>
    </div>

    <!-- Pending/Active Articles -->
    <?php $articles = $articleObj->getArticles(); ?>
    <?php foreach ($articles as $article) { ?>
      <div class="article-card">
        <h5><?php echo $article['title']; ?></h5>
        <small class="text-muted">
          <strong><?php echo $article['username']; ?></strong> • <?php echo $article['created_at']; ?>
        </small>

        <!-- Status -->
        <p class="mt-2">
          <?php if ($article['is_active'] == 0) { ?>
            <span class="status-badge status-pending">Pending</span>
          <?php } else { ?>
            <span class="status-badge status-active">Active</span>
          <?php } ?>
        </p>

        <!-- Content -->
        <p><?php echo $article['content']; ?></p>

        <!-- Image -->
        <?php if (!empty($article['image_path'])) { ?>
          <div class="mb-3">
            <img src="../<?php echo $article['image_path']; ?>" class="img-fluid rounded" alt="Article Image" style="max-height: 300px;">
          </div>
        <?php } ?>

        <!-- Status Update -->
        <form class="updateArticleStatus mb-3">
          <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
          <select name="is_active" class="form-control form-control-sm is_active_select" article_id="<?php echo $article['article_id']; ?>">
            <option value="">Change status...</option>
            <option value="0">Pending</option>
            <option value="1">Active</option>
          </select>
        </form>

        <!-- Delete -->
        <form class="deleteArticleForm d-inline">
          <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
          <button type="submit" class="btn btn-sm btn-outline-danger deleteArticleBtn">Delete</button>
        </form>
      </div>
    <?php } ?>
  </div>

  <script>
    // Update article status
    $('.is_active_select').on('change', function (event) {
      event.preventDefault();
      var formData = {
        article_id: $(this).attr('article_id'),
        status: $(this).val(),
        updateArticleVisibility: 1
      }
      if (formData.article_id !== "" && formData.status !== "") {
        $.ajax({
          type: "POST",
          url: "core/handleForms.php",
          data: formData,
          success: function (data) {
            if (data) {
              location.reload();
            } else {
              alert("Visibility update failed");
            }
          }
        })
      }
    });

    // Delete article
    $('.deleteArticleForm').on('submit', function (event) {
      event.preventDefault();
      var formData = {
        article_id: $(this).find('.article_id').val(),
        deleteArticleBtn: 1
      }
      if (confirm("Are you sure you want to delete this article?")) {
        $.ajax({
          type: "POST",
          url: "core/handleForms.php",
          data: formData,
          success: function (data) {
            if (data) {
              location.reload();
            } else {
              alert("Deletion failed");
            }
          }
        })
      }
    });
  </script>
</body>
</html>