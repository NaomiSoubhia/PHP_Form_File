<?php
session_start();
// Make sure the user is logged in before they can access this page
require "includes/auth.php";

require "includes/header_admin.php";
require "includes/connect.php";

// Get user ID

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$userId = $_SESSION['user_id']; 

// Prepare SQL to get the most recent photo
$sql = "SELECT image_path 
        FROM photos 
        WHERE user_id = :user_id 
        ORDER BY created_at DESC 
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $userId);
$stmt->execute();

// Fetch the most recent photo
$photo = $stmt->fetch(PDO::FETCH_ASSOC);

?>
 
<div class="ms-5 ">
  <a href="form.php" class="btn btn-secondary d-block m-2" style="width:150px;">Upload photo</a>
  <?php if (!empty($photo['image_path'])): ?>
    <img src="<?= htmlspecialchars($photo['image_path']); ?>" class="rounded w-50 d-block" alt="Profile Image">
   
    <?php else: ?>
    <p>No image found</p>
  <?php endif; ?>
   
</div>


<?php require "includes/footer.php";?>