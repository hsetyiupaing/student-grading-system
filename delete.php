<?php
// delete.php - Delete a student record

require "db.php";

$id = $_GET['id'] ?? null;

if ($id === null || !ctype_digit((string)$id)) {
    die("Invalid or missing student ID.");
}

$stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: view.php?deleted=1");
exit;