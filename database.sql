USE student_grades;

CREATE TABLE students (
    student_id INT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    course VARCHAR(50),
    grade DECIMAL(3, 2)
);