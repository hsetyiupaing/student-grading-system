<?php
// edit.php - Edit an existing student record

require "db.php";

$errors = [];
$student = null;

// Determine which student we're editing: from GET (loading form) or POST (submitting form)
$id = $_GET['id'] ?? $_POST['student_id'] ?? null;

if ($id === null || !ctype_digit((string)$id)) {
    die("Invalid or missing student ID.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name  = trim($_POST["last_name"] ?? "");
    $course     = trim($_POST["course"] ?? "");
    $grade      = trim($_POST["grade"] ?? "");

    if ($first_name === "") $errors[] = "First name is required.";
    if ($last_name === "")  $errors[] = "Last name is required.";
    if ($course === "")     $errors[] = "Course is required.";
    if ($grade === "" || !is_numeric($grade) || $grade < 0 || $grade > 9.99) {
        $errors[] = "Grade must be a number between 0 and 9.99.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "UPDATE students SET first_name = ?, last_name = ?, course = ?, grade = ? WHERE student_id = ?"
        );
        $stmt->bind_param("sssdi", $first_name, $last_name, $course, $grade, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: view.php?updated=1");
        exit;
    }

    // Keep submitted values so the form re-shows what the user typed
    $student = [
        "student_id" => $id,
        "first_name" => $first_name,
        "last_name"  => $last_name,
        "course"     => $course,
        "grade"      => $grade,
    ];
} else {
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();

    if (!$student) {
        die("Student not found.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student - Student Grading System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Student</h1>
        <nav class="nav-links">
            <a href="view.php">View All Students</a>
        </nav>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="edit.php?id=<?= urlencode($student['student_id']) ?>" class="grade-form">
            <label>Student ID</label>
            <input type="text" value="<?= htmlspecialchars($student['student_id']) ?>" disabled>
            <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>">

            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required>

            <label for="course">Course</label>
            <input type="text" id="course" name="course" value="<?= htmlspecialchars($student['course']) ?>" required>

            <label for="grade">Grade (e.g. 3.75)</label>
            <input type="number" step="0.01" min="0" max="9.99" id="grade" name="grade" value="<?= htmlspecialchars($student['grade']) ?>" required>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>