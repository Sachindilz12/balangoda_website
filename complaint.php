<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $complaint_text = $_POST['complaint_text'];
    $status = 'Pending';
    $progress_status = 'Submitted';

    $attachment = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["attachment"]["name"]);
        move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file);
        $attachment = $target_file;
    }

    $stmt = $conn->prepare("INSERT INTO complaints (user_id, complaint_text, status, attachment, progress_status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $user_id, $complaint_text, $status, $attachment, $progress_status);
    $stmt->execute();
    $stmt->close();
}

$complaints = $conn->query("SELECT * FROM complaints ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="source/css/complaint.css">
    <link rel="stylesheet" href="source/css/main.css">

    <title>Complaint System</title>
</head>
<body>
    <div class="container">
        <h1>Submit a Complaint</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="user_id" placeholder="Your User ID" required>
            <textarea name="complaint_text" placeholder="Describe your complaint..." required></textarea>
            <input type="file" name="attachment">
            <button type="submit">Submit Complaint</button>
        </form>

        <h2>Your Complaints</h2>
        <div class="complaint-list">
            <?php while ($row = $complaints->fetch_assoc()): ?>
                <div class="complaint-box">
                    <p><strong>User ID:</strong> <?php echo htmlspecialchars($row['user_id']); ?></p>
                    <p><strong>Complaint:</strong> <?php echo htmlspecialchars($row['complaint_text']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                    <p><strong>Progress Status:</strong> <?php echo htmlspecialchars($row['progress_status']); ?></p>
                    <p><strong>Date Submitted:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
                    <?php if ($row['attachment']): ?>
                        <p><strong>Attachment:</strong> <a href="<?php echo htmlspecialchars($row['attachment']); ?>" target="_blank">View Attachment</a></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
