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
  <title>User Articles</title>

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
    .article-card h4 {
      margin-bottom: 10px;
    }
    .edit-label {
      font-size: 0.9rem;
      font-weight: 600;
      color: #555;
    }
    .btn-custom {
      background: #965f37;
      color: #fff;
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
      <h2>Your Articles</h2>
      <p class="text-muted">Write, edit, and manage your contributions</p>
    </div>

    <!-- Edit Requests Section -->
    <?php $editRequests = $articleObj->getPendingEditRequests($_SESSION['user_id']); ?>
    <?php if (!empty($editRequests)) { ?>
    <div class="form-box">
      <h4 class="mb-3">Pending Edit Requests</h4>
      <?php foreach ($editRequests as $request) { ?>
        <div class="card mb-3 shadow-sm">
          <div class="card-body">
            <h6><?php echo $request['title']; ?></h6>
            <p class="text-muted">Requested by: <strong><?php echo $request['requester_name']; ?></strong></p>
            <p class="text-muted">Date: <?php echo $request['created_at']; ?></p>
            
            <form action="core/handleForms.php" method="POST" class="d-inline">
              <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
              <input type="hidden" name="status" value="accepted">
              <button type="submit" class="btn btn-success btn-sm" name="respondToEditRequest">Accept</button>
            </form>
            
            <form action="core/handleForms.php" method="POST" class="d-inline">
              <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
              <input type="hidden" name="status" value="rejected">
              <button type="submit" class="btn btn-danger btn-sm" name="respondToEditRequest">Reject</button>
            </form>
          </div>
        </div>
      <?php } ?>
    </div>
    <?php } ?>

    <!-- Instructions -->
    <p class="text-center text-muted">💡 Double click an article to edit it</p>

    <!-- Articles List -->
    <?php $articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); ?>
    <?php foreach ($articles as $article) { ?>
      <div class="article-card articleCard">
        <h4><?php echo $article['title']; ?></h4>
        <small class="text-muted">
          <strong><?php echo $article['username']; ?></strong> • <?php echo $article['created_at']; ?>
        </small>
        
        <?php if (!empty($article['image_path'])) { ?>
          <div class="mb-3">
            <img src="../<?php echo $article['image_path']; ?>" class="img-fluid rounded" alt="Article Image" style="max-height: 300px;">
          </div>
        <?php } ?>
        
        <p class="mt-2"><?php echo $article['content']; ?></p>

        <!-- Delete Form -->
        <form class="deleteArticleForm">
          <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
          <input type="submit" class="btn btn-danger btn-sm float-right deleteArticleBtn" value="Delete">
        </form>

        <!-- Edit Form (hidden until dblclick) -->
        <div class="updateArticleForm d-none mt-4">
          <h6 class="edit-label">Edit this article</h6>
          <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <input type="text" class="form-control" name="title" value="<?php echo $article['title']; ?>">
            </div>
            <div class="form-group">
              <textarea name="description" class="form-control" rows="3"><?php echo $article['content']; ?></textarea>
            </div>
            <div class="form-group">
              <label for="edit_image">Update Image (Optional)</label>
              <input type="file" class="form-control-file" name="image" id="edit_image" accept="image/*">
            </div>
            <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
            <button type="submit" class="btn btn-success btn-sm" name="editArticleBtn">Save Changes</button>
          </form>
        </div>
      </div>
    <?php } ?>
  </div>

  <script>
    // Toggle edit form on double click
    $('.articleCard').on('dblclick', function () {
      $(this).find('.updateArticleForm').toggleClass('d-none');
    });

    // Handle delete with AJAX
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
    })
  </script>
</body>
</html>