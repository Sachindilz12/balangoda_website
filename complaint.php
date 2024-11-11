<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // Use the logged-in username

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $category = $_POST['category'];
    $location = $_POST['location'];
    $priority = $_POST['priority'];
    $contact = $_POST['contact'];
    $complaint_text = $_POST['complaint_text'];
    $complaint_date = $_POST['complaint_date'];
    $complaint_time = $_POST['complaint_time'];
    $additional_comments = $_POST['additional_comments'];
    $status = 'Pending';
    $progress_status = 'Submitted';

    // Initialize the attachment variable
    $attachment = null;

    // Handle file upload
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $target_dir = "uploads/";

        // Check if the directory exists, if not, create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // 0777 gives full permissions
        }

        $target_file = $target_dir . basename($_FILES["attachment"]["name"]);

        // Move the uploaded file
        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
            $attachment = $target_file;
        } else {
            echo "Error: File upload failed.";
        }
    }

    // Insert the complaint into the database
    $stmt = $conn->prepare("INSERT INTO complaints 
        (username, category, location, priority, contact, complaint_text, complaint_date, complaint_time, additional_comments, status, progress_status, attachment, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssssssssss", 
        $username, $category, $location, $priority, $contact, $complaint_text, $complaint_date, $complaint_time, $additional_comments, $status, $progress_status, $attachment);
    $stmt->execute();
    $stmt->close();
}

// Fetch complaints from the database
$complaints = $conn->prepare("SELECT * FROM complaints WHERE username = ? ORDER BY created_at DESC");
$complaints->bind_param("s", $username);
$complaints->execute();
$result = $complaints->get_result();
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
            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="">Select Category</option>
                <option value="Noise">Noise</option>
                <option value="Water Supply">Water Supply</option>
                <option value="Road Maintenance">Road Maintenance</option>
                <option value="Waste Management">Waste Management</option>
                <option value="Other">Other</option>
            </select>

            <label for="location">Location:</label>
            <input type="text" name="location" placeholder="Enter location" required>

            <label for="priority">Priority:</label>
            <select name="priority" id="priority" required>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
                <option value="Urgent">Urgent</option>
            </select>

            <label for="contact">Contact Number:</label>
            <input type="tel" name="contact" placeholder="Your contact number" required>

            <label for="complaint_text">Complaint Description:</label>
            <textarea name="complaint_text" placeholder="Describe your complaint..." required></textarea>

            <label for="complaint_date">Date of Incident:</label>
            <input type="date" name="complaint_date" required>

            <label for="complaint_time">Time of Incident:</label>
            <input type="time" name="complaint_time">

            <label for="attachment">Attach Evidence (images, videos, etc.):</label>
            <input type="file" name="attachment">

            <label for="additional_comments">Additional Comments:</label>
            <textarea name="additional_comments" placeholder="Any additional information..."></textarea>

            <button type="submit">Submit Complaint</button>
        </form>

        <h2>Your Complaints</h2>
        <div class="complaint-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="complaint-box">
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                    <p><strong>Priority:</strong> <?php echo htmlspecialchars($row['priority']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact']); ?></p>
                    <p><strong>Complaint:</strong> <?php echo htmlspecialchars($row['complaint_text']); ?></p>
                    <p><strong>Date Submitted:</strong> <?php echo htmlspecialchars($row['complaint_date']); ?></p>
                    <p><strong>Time of Incident:</strong> <?php echo htmlspecialchars($row['complaint_time']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                    <p><strong>Progress Status:</strong> <?php echo htmlspecialchars($row['progress_status']); ?></p>
                    <?php if ($row['attachment']): ?>
                        <p><strong>Attachment:</strong> <a href="<?php echo htmlspecialchars($row['attachment']); ?>" target="_blank">View Attachment</a></p>
                    <?php endif; ?>
                    <p><strong>Additional Comments:</strong> <?php echo htmlspecialchars($row['additional_comments']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

<?php
$complaints->close();
$conn->close();
?>
