<?php
// Make sure the user is logged in before they can access this page
require "includes/auth.php";

// Connect to the database
require "includes/connect.php";

// Show the admin-style header/navigation
require "includes/header_admin.php";

//Get user ID

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$userId = $_SESSION['user_id']; 

// Array for validation errors
$errors = [];

// Success message
$success = "";


// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    // This will store the image path for the database
    $imagePath = null;



    //Check whether a file was uploaded
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        //Make sure upload completed successfully 
        if ($_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "There was a problem uploading your file!";
        } else {
            //Only allow a few file types 
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            //Detect the real MIME type of the file 
            $detectedType = mime_content_type($_FILES['profile_image']['tmp_name']);
            if (!in_array($detectedType, $allowedTypes, true)) {
                $errors[] = "Only JPG, PNG and WebP allowed";
            } else {
                //Build the file name and move it to where we want it to go (uploads)
                //Get the file extension 
                $extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                //create a unique filename so uploaded files don't overwrite 
                $safeFilename = uniqid('photo_', true) . '.' . strtolower($extension);
                //build the full server path where the file will be stored 
                $destination = __DIR__ . '/uploads/' . $safeFilename;
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination)) {
                    //save the relative path to the database
                    $imagePath = 'uploads/' . $safeFilename; 
                } else {
                    $errors[] = "Image uploaded failed!"; 
                }
            }
        }
    }

    // If there are no errors, insert the path 
    if (empty($errors)) {
        $sql = "INSERT INTO photos (image_path, user_id)
                VALUES (:image_path, :user_id)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':image_path', $imagePath);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $success = "Photo added successfully!";
    }
}
?>

<main class="container mt-4">
    <h1>Add Profile Photo</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <h3>Please fix the following:</h3>
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success !== ""): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    <!--enctype="multipart/form-data" required for uploads, will not send properly if not included -->
    <form method="post" enctype="multipart/form-data" class="mt-3">
        


        <label for="image" class="form-label">Image</label>
        <input
            type="file"
            id="profile_image"
            name="profile_image"
            class="form-control mb-4"
            accept=".jpg,.jpeg,.png,.webp">

        <button type="submit" class="btn btn-primary">Add Photo</button>
    </form>
</main>

<?php require "includes/footer.php"; ?>