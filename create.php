<?php
// create.php - Add a new student record

require "db.php";

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = trim($_POST["student_id"] ?? "");
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name  = trim($_POST["last_name"] ?? "");
    $course     = trim($_POST["course"] ?? "");
    $grade      = trim($_POST["grade"] ?? "");

    // Basic validation
    if ($student_id === "" || !ctype_digit($student_id)) {
        $errors[] = "Student ID must be a whole number.";
    }
    if ($first_name === "") {
        $errors[] = "First name is required.";
    }
    if ($last_name === "") {
        $errors[] = "Last name is required.";
    }
    if ($course === "") {
        $errors[] = "Course is required.";
    }
    if ($grade === "" || !is_numeric($grade) || $grade < 0 || $grade > 9.99) {
        $errors[] = "Grade must be a number between 0 and 9.99.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "INSERT INTO students (student_id, first_name, last_name, course, grade) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("isssd", $student_id, $first_name, $last_name, $course, $grade);

        if ($stmt->execute()) {
            $success = true;
        } else {
            if ($conn->errno === 1062) {
                $errors[] = "A student with that ID already exists.";
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student - Student Grading System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Student</h1>
        <nav class="nav-links">
            <a href="view.php">View All Students</a>
        </nav>

        <?php if ($success): ?>
            <p class="alert alert-success">Student added successfully!</p>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="create.php" class="grade-form">
            <label for="student_id">Student ID</label>
            <input type="number" id="student_id" name="student_id" value="<?= htmlspecialchars($_POST['student_id'] ?? '') ?>" required>

            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>" required>

            <label for="course">Course</label>
            <input type="text" id="course" name="course" value="<?= htmlspecialchars($_POST['course'] ?? '') ?>" required>

            <label for="grade">Grade (e.g. 3.75)</label>
            <input type="number" step="0.01" min="0" max="9.99" id="grade" name="grade" value="<?= htmlspecialchars($_POST['grade'] ?? '') ?>" required>

            <button type="submit">Add Student</button>
        </form>
    </div>
</body>
</html>