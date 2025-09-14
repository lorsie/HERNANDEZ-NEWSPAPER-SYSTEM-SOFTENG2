<?php  

require_once 'Database.php';
require_once 'User.php';
/**
 * Class for handling Article-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Article extends Database {
    /**
     * Creates a new article.
     * @param string $title The article title.
     * @param string $content The article content.
     * @param int $author_id The ID of the author.
     * @param string|null $image_path The path to the uploaded image.
     * @return int The ID of the newly created article.
     */
    public function createArticle($title, $content, $author_id, $image_path = null) {
        $sql = "INSERT INTO articles (title, content, author_id, is_active, image_path) VALUES (?, ?, ?, 0, ?)";
        return $this->executeNonQuery($sql, [$title, $content, $author_id, $image_path]);
    }

    /**
     * Retrieves articles from the database.
     * @param int|null $id The article ID to retrieve, or null for all articles.
     * @return array
     */
    public function getArticles($id = null) {
        if ($id) {
            $sql = "SELECT * FROM articles WHERE article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM articles JOIN school_publication_users ON articles.author_id = school_publication_users.user_id ORDER BY articles.created_at DESC";
        return $this->executeQuery($sql);
    }

    public function getActiveArticles($id = null) {
        if ($id) {
            $sql = "SELECT * FROM articles WHERE article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id 
                WHERE is_active = 1 ORDER BY articles.created_at DESC";
                
        return $this->executeQuery($sql);
    }

    public function getArticlesByUserID($user_id) {
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id
                WHERE author_id = ? ORDER BY articles.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    /**
     * Updates an article.
     * @param int $id The article ID to update.
     * @param string $title The new title.
     * @param string $content The new content.
     * @param string|null $image_path The new image path.
     * @return int The number of affected rows.
     */
    public function updateArticle($id, $title, $content, $image_path = null) {
        $sql = "UPDATE articles SET title = ?, content = ?, image_path = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$title, $content, $image_path, $id]);
    }
    
    /**
     * Toggles the visibility (is_active status) of an article.
     * This operation is restricted to admin users only.
     * @param int $id The article ID to update.
     * @param bool $is_active The new visibility status.
     * @return int The number of affected rows.
     */
    public function updateArticleVisibility($id, $is_active) {
        $userModel = new User();
        if (!$userModel->isAdmin()) {
            return 0;
        }
        $sql = "UPDATE articles SET is_active = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [(int)$is_active, $id]);
    }


    /**
     * Deletes an article and notifies the author.
     * @param int $id The article ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteArticle($id) {
        // Get article info before deletion
        $article = $this->getArticles($id);
        if ($article) {
            // Notify the author
            $this->createNotification($article['author_id'], 
                "Your article '{$article['title']}' has been deleted by an admin.", 
                'article_deleted', $id);
        }
        
        $sql = "DELETE FROM articles WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    /**
     * Creates a notification for a user.
     * @param int $user_id The user ID to notify.
     * @param string $message The notification message.
     * @param string $type The notification type.
     * @param int|null $article_id The article ID if related.
     * @return int The number of affected rows.
     */
    public function createNotification($user_id, $message, $type, $article_id = null) {
        $sql = "INSERT INTO notifications (user_id, message, type, article_id) VALUES (?, ?, ?, ?)";
        return $this->executeNonQuery($sql, [$user_id, $message, $type, $article_id]);
    }

    /**
     * Gets notifications for a user.
     * @param int $user_id The user ID.
     * @return array Array of notifications.
     */
    public function getNotifications($user_id) {
        $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    /**
     * Marks a notification as read.
     * @param int $notification_id The notification ID.
     * @return int The number of affected rows.
     */
    public function markNotificationAsRead($notification_id) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE notification_id = ?";
        return $this->executeNonQuery($sql, [$notification_id]);
    }

    /**
     * Creates an edit request for an article.
     * @param int $article_id The article ID.
     * @param int $requester_id The user requesting edit access.
     * @return int The number of affected rows.
     */
    public function createEditRequest($article_id, $requester_id) {
        // Check if request already exists
        $sql = "SELECT COUNT(*) as count FROM edit_requests WHERE article_id = ? AND requester_id = ? AND status = 'pending'";
        $result = $this->executeQuerySingle($sql, [$article_id, $requester_id]);
        
        if ($result['count'] > 0) {
            return 0; // Request already exists
        }

        $sql = "INSERT INTO edit_requests (article_id, requester_id) VALUES (?, ?)";
        $result = $this->executeNonQuery($sql, [$article_id, $requester_id]);
        
        if ($result) {
            // Get article info to notify author
            $article = $this->getArticles($article_id);
            $requester = $this->executeQuerySingle("SELECT username FROM school_publication_users WHERE user_id = ?", [$requester_id]);
            
            if ($article && $requester) {
                $this->createNotification($article['author_id'], 
                    "User '{$requester['username']}' has requested edit access to your article '{$article['title']}'.", 
                    'edit_request', $article_id);
            }
        }
        
        return $result;
    }

    /**
     * Responds to an edit request (accept/reject).
     * @param int $request_id The request ID.
     * @param string $status 'accepted' or 'rejected'.
     * @return int The number of affected rows.
     */
    public function respondToEditRequest($request_id, $status) {
        // Get request info
        $sql = "SELECT er.*, a.title, a.author_id, u.username as requester_name 
                FROM edit_requests er 
                JOIN articles a ON er.article_id = a.article_id 
                JOIN school_publication_users u ON er.requester_id = u.user_id 
                WHERE er.request_id = ?";
        $request = $this->executeQuerySingle($sql, [$request_id]);
        
        if (!$request) {
            return 0;
        }

        // Update request status
        $sql = "UPDATE edit_requests SET status = ? WHERE request_id = ?";
        $result = $this->executeNonQuery($sql, [$status, $request_id]);
        
        if ($result) {
            // Notify requester
            $message = $status === 'accepted' 
                ? "Your edit request for '{$request['title']}' has been accepted!"
                : "Your edit request for '{$request['title']}' has been rejected.";
            
            $this->createNotification($request['requester_id'], $message, 
                $status === 'accepted' ? 'edit_accepted' : 'edit_rejected', $request['article_id']);
            
            // If accepted, create shared article entry
            if ($status === 'accepted') {
                $this->shareArticle($request['article_id'], $request['requester_id'], $request['author_id']);
            }
        }
        
        return $result;
    }

    /**
     * Shares an article with another user.
     * @param int $article_id The article ID.
     * @param int $shared_with_user_id The user ID to share with.
     * @param int $shared_by_user_id The user ID sharing the article.
     * @return int The number of affected rows.
     */
    public function shareArticle($article_id, $shared_with_user_id, $shared_by_user_id) {
        // Check if already shared
        $sql = "SELECT COUNT(*) as count FROM shared_articles WHERE article_id = ? AND shared_with_user_id = ?";
        $result = $this->executeQuerySingle($sql, [$article_id, $shared_with_user_id]);
        
        if ($result['count'] > 0) {
            return 0; // Already shared
        }

        $sql = "INSERT INTO shared_articles (article_id, shared_with_user_id, shared_by_user_id) VALUES (?, ?, ?)";
        return $this->executeNonQuery($sql, [$article_id, $shared_with_user_id, $shared_by_user_id]);
    }

    /**
     * Gets shared articles for a user.
     * @param int $user_id The user ID.
     * @return array Array of shared articles.
     */
    public function getSharedArticles($user_id) {
        $sql = "SELECT a.*, u.username, u.is_admin, sa.created_at as shared_at, 
                       sb.username as shared_by_name
                FROM shared_articles sa 
                JOIN articles a ON sa.article_id = a.article_id 
                JOIN school_publication_users u ON a.author_id = u.user_id 
                JOIN school_publication_users sb ON sa.shared_by_user_id = sb.user_id 
                WHERE sa.shared_with_user_id = ? 
                ORDER BY sa.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    /**
     * Gets pending edit requests for a user's articles.
     * @param int $user_id The user ID.
     * @return array Array of pending edit requests.
     */
    public function getPendingEditRequests($user_id) {
        $sql = "SELECT er.*, a.title, u.username as requester_name 
                FROM edit_requests er 
                JOIN articles a ON er.article_id = a.article_id 
                JOIN school_publication_users u ON er.requester_id = u.user_id 
                WHERE a.author_id = ? AND er.status = 'pending' 
                ORDER BY er.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }
}
?>
