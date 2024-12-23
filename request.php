<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db = 'task_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_type = $_POST['request_type'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $status = 'Submitted';
    
    // Initialize the file path
    $file_path = null;

    // Handle file upload
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $target_dir = 'uploads/';

        // Check if the directory exists, if not, create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // 0777 gives full permissions
        }

        // Define the target file path
        $file_path = $target_dir . basename($_FILES['attachment']['name']);

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $file_path)) {
            echo "Error: File upload failed.";
        }
    }

    // Insert the request into the database
    $stmt = $conn->prepare("INSERT INTO requests (request_type, description, priority, status, attachment) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $request_type, $description, $priority, $status, $file_path);
    $stmt->execute();
    $stmt->close();
}

$requests = $conn->query("SELECT * FROM requests");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="source/css/request.css">
    <link rel="stylesheet" href="source/css/main.css">

    <title>Service Requests</title>
</head>
<body>
    <div class="container">
        <h1>Service Requests and Permit Applications</h1>
        <form method="POST" enctype="multipart/form-data" class="request-form">
            <label for="request_type">Request Type:</label>
            <select name="request_type" id="request_type" required>
                <option value="">Select a request type</option>
                <option value="Garbage Collection">Garbage Collection</option>
                <option value="Birth Certificate">Birth Certificate</option>
                <option value="Construction Permit">Construction Permit</option>
                <option value="Other">Other</option>
            </select>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            <label for="priority">Priority Level:</label>
            <select name="priority" id="priority" required>
                <option value="Urgent">Urgent</option>
                <option value="High">High</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
            </select>
            <label for="attachment">Upload Attachment:</label>
            <input type="file" name="attachment" id="attachment" accept=".jpg, .jpeg, .png, .pdf">
            <button type="submit">Submit Request</button>
        </form>

        <h2>Submitted Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>Request Type</th>
                    <th>Description</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Attachment</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $requests->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['request_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['priority']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php if ($row['attachment']): ?>
                                <a href="<?php echo htmlspecialchars($row['attachment']); ?>" target="_blank">View</a>
                            <?php else: ?>
                                No attachment
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
