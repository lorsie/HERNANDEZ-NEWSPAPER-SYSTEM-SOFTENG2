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
      color: #333;
    }

    /* Containers */
    .form-box, .article-card, .card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 12px;
      border: none;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      transition: transform 0.2s ease-in-out;
    }
    .card:hover {
      transform: translateY(-3px);
    }

    /* Inputs */
    .form-control, .form-control-file {
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    /* Buttons */
    .btn {
      border-radius: 8px;
      font-weight: 600;
    }
    .btn-danger {
      background-color: #965f37 !important; 
      border: none;
    }
    .btn-danger:hover {
      background-color: #965f37 !important;
    }
    .btn-primary {
      background-color: #965f37 !important; 
      border: none;
    }
    .btn-primary:hover {
      background-color: #965f37 !important;
    }

    /* Headings */
    .page-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: #ffffffff;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
      margin: 30px 0 20px;
      text-align: center;
    }
    .status-pending {
      color: #efd442 !important;
      font-weight: 600;
    }
    .status-active {
      color: #4b2814 !important;
      font-weight: 600;
    }
    h1, h4 {
      font-weight: 700;
      color: #333;
    }
    small {
      color: #555;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-7">

        <!-- Submit form -->
        <div class="card p-4 mb-5">
          <h4 class="mb-3 text-center">Submit a New Article</h4>
          <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <input type="text" class="form-control" name="title" placeholder="Enter article title" required>
            </div>
            <div class="form-group">
              <textarea name="description" class="form-control" rows="4" placeholder="Write your article here..." required></textarea>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">Article Image (Optional)</label>
              <input type="file" class="form-control-file" name="image" accept="image/*">
            </div>
            <div class="text-right">
              <button type="submit" class="btn btn-danger px-4" name="insertArticleBtn">Submit</button>
            </div>
          </form>
        </div>

        <!-- Instruction -->
        <h2 class="page-title">Double click an article to edit</h2>

        <!-- Article list -->
        <?php $articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); ?>
        <?php foreach ($articles as $article) { ?>
          <div class="card article-card mb-4">
            <div class="card-body">
              <h1 class="h4"><?php echo $article['title']; ?></h1>
              <small class="d-block mb-2"><?php echo $article['username'] ?> • <?php echo $article['created_at']; ?></small>
              
              <?php if (!empty($article['image_path'])) { ?>
                <div class="mb-3 text-center">
                  <img src="../<?php echo $article['image_path']; ?>" class="img-fluid rounded" alt="Article Image" style="max-height: 250px;">
                </div>
              <?php } ?>

              <?php if ($article['is_active'] == 0) { ?>
                <p class="status-pending">Status: PENDING</p>
              <?php } ?>
              <?php if ($article['is_active'] == 1) { ?>
                <p class="status-active">Status: ACTIVE</p>
              <?php } ?>

              <p><?php echo $article['content']; ?></p>

              <!-- Delete -->
              <form class="deleteArticleForm mt-3">
                <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
                <button type="submit" class="btn btn-danger float-right deleteArticleBtn">Delete</button>
              </form>

              <!-- Edit -->
              <div class="updateArticleForm d-none mt-4">
                <h4 class="mb-3">Edit Article</h4>
                <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                  <div class="form-group">
                    <input type="text" class="form-control" name="title" value="<?php echo $article['title']; ?>">
                  </div>
                  <div class="form-group">
                    <textarea name="description" class="form-control" rows="4"><?php echo $article['content']; ?></textarea>
                  </div>
                  <div class="form-group">
                    <label class="font-weight-bold">Update Image (Optional)</label>
                    <input type="file" class="form-control-file" name="image" accept="image/*">
                  </div>
                  <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                  <div class="text-right">
                    <button type="submit" class="btn btn-primary px-4" name="editArticleBtn">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php } ?> 

      </div>
    </div>
  </div>

  <script>
    // Toggle edit form
    $('.articleCard').on('dblclick', function () {
      var updateArticleForm = $(this).find('.updateArticleForm');
      updateArticleForm.toggleClass('d-none');
    });

    // Handle delete via AJAX
    $('.deleteArticleForm').on('submit', function (event) {
      event.preventDefault();
      var formData = {
        article_id: $(this).find('.article_id').val(),
        deleteArticleBtn: 1
      }
      if (confirm("Are you sure you want to delete this article?")) {
        $.ajax({
          type:"POST",
          url: "core/handleForms.php",
          data:formData,
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