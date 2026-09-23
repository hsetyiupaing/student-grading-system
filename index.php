<?php
// view.php - List all student records

require "db.php";

$deleted = isset($_GET['deleted']);
$updated = isset($_GET['updated']);

$result = $conn->query("SELECT * FROM students ORDER BY student_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Students - Student Grading System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Student Grades</h1>
        <nav class="nav-links">
            <a href="create.php">+ Add Student</a>
        </nav>

        <?php if ($deleted): ?>
            <p class="alert alert-success">Student deleted successfully.</p>
        <?php endif; ?>
        <?php if ($updated): ?>
            <p class="alert alert-success">Student updated successfully.</p>
        <?php endif; ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <table class="grade-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Course</th>
                        <th>Grade</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['student_id']) ?></td>
                            <td><?= htmlspecialchars($row['first_name']) ?></td>
                            <td><?= htmlspecialchars($row['last_name']) ?></td>
                            <td><?= htmlspecialchars($row['course']) ?></td>
                            <td><?= htmlspecialchars($row['grade']) ?></td>
                            <td class="actions">
                                <a class="btn-edit" href="edit.php?id=<?= urlencode($row['student_id']) ?>">Edit</a>
                                <a class="btn-delete" href="delete.php?id=<?= urlencode($row['student_id']) ?>"
                                   onclick="return confirm('Delete this student record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No students found. <a href="create.php">Add one now.</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>