<?php  
require_once '../classloader.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password)) {
					header("Location: ../login.php");
				}

				else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
				}
			}

			else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
			}
		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}
	}
	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginUser($email, $password)) {
			header("Location: ../index.php");
		}
		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
}

if (isset($_POST['insertAdminArticleBtn'])) {
	$title = $_POST['title'];
	$description = $_POST['description'];
	$author_id = $_SESSION['user_id'];
	$image_path = null;
	if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
		$targetDir = '../../uploads/';
		if (!file_exists($targetDir)) { @mkdir($targetDir, 0777, true); }
		$fileName = time() . '_' . basename($_FILES['image']['name']);
		$targetFile = $targetDir . $fileName;
		if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
			$image_path = 'uploads/' . $fileName;
		}
	}
	if ($articleObj->createArticle($title, $description, $author_id, $image_path)) {
		header("Location: ../index.php");
	}
}

if (isset($_POST['editArticleBtn'])) {
	$title = $_POST['title'];
	$description = $_POST['description'];
	$article_id = $_POST['article_id'];
	$image_path = null;
	if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
		$targetDir = '../../uploads/';
		if (!file_exists($targetDir)) { @mkdir($targetDir, 0777, true); }
		$fileName = time() . '_' . basename($_FILES['image']['name']);
		$targetFile = $targetDir . $fileName;
		if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
			$image_path = 'uploads/' . $fileName;
		}
	}
	if ($articleObj->updateArticle($article_id, $title, $description, $image_path)) {
		header("Location: ../articles_submitted.php");
	}
}

if (isset($_POST['deleteArticleBtn'])) {
	$article_id = $_POST['article_id'];
	if ($articleObj->deleteArticle($article_id)) {
		$_SESSION['message'] = "Article deleted successfully!";
		$_SESSION['status'] = '200';
	} else {
		$_SESSION['message'] = "Failed to delete article.";
		$_SESSION['status'] = '400';
	}
	header("Location: ../articles_submitted.php");
}

if (isset($_POST['respondToEditRequest'])) {
	$request_id = $_POST['request_id'];
	$status = $_POST['status'];
	
	if ($articleObj->respondToEditRequest($request_id, $status)) {
		$_SESSION['message'] = "Edit request " . $status . " successfully!";
		$_SESSION['status'] = '200';
	} else {
		$_SESSION['message'] = "Failed to respond to edit request.";
		$_SESSION['status'] = '400';
	}
	header("Location: ../articles_submitted.php");
}

if (isset($_POST['updateArticleVisibility'])) {
	$article_id = $_POST['article_id'];
	$status = $_POST['status'];
	echo $articleObj->updateArticleVisibility($article_id,$status);
}

if (isset($_POST['deleteArticleBtn'])) {
    require_once '../classes/Database.php';
    require_once '../classes/Article.php';

    $article = new Article();
    $article_id = $_POST['article_id'];

    $deleted = $article->deleteArticle($article_id);
    echo $deleted ? 1 : 0;
    exit;
}
