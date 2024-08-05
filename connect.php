<?php
// Retrieve and sanitize form data
$Name = isset($_POST['Name']) ? trim($_POST['Name']) : null;
$Email = isset($_POST['Email']) ? trim($_POST['Email']) : null;
$password = isset($_POST['password']) ? trim($_POST['password']) : null;
$confirmationpassword = isset($_POST['confirmationpassword']) ? trim($_POST['confirmationpassword']) : null;

// Data validation
if ($Name === null || $Email === null || $password === null || $confirmationpassword === null) {
    die('Some form fields are missing.');
}

if (empty($Name) || empty($Email) || empty($password) || empty($confirmationpassword)) {
    die('All fields are required.');
}

// Check if passwords match
if ($password !== $confirmationpassword) {
    die('Passwords do not match.');
}

// Hash the password before storing
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'html');

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
} else {
    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO html (name, email, password) VALUES (?, ?, ?)");

    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sss", $Name, $Email, $hashed_password);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "Registration successful...";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>