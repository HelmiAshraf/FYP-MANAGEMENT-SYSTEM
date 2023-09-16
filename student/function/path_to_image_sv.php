<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "fypms";

// Create a database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Retrieve the image based on sv_id
if(isset($_GET['sv_id'])) {
    $sv_id = $_GET['sv_id'];
    $sql = "SELECT sv_image FROM supervisor WHERE sv_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sv_id);
    $stmt->execute();
    
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($imageData);
        $stmt->fetch();
        
        // Set appropriate headers for an image (JPEG in this case)
        header("Content-Type: image/jpeg");
        
        // Output the image data
        echo $imageData;
    } else {
        // Image not found
        echo "Image not found.";
    }
    
    $stmt->close();
} else {
    // No sv_id provided
    echo "No sv_id provided.";
}

$conn->close();
?>
