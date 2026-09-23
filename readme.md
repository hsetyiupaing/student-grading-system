# Student Grading System

A simple PHP + MySQL CRUD application for managing student records: student ID, name, course, and grade.

## Features

- **View** all student records in a table
- **Add** a new student
- **Edit** an existing student's details
- **Delete** a student record

## Tech Stack

- PHP 8+ (uses `mysqli` with prepared statements)
- MySQL / MariaDB
- Plain HTML/CSS (no framework)

## Project Structure

```
student-grading-system/
├── database.sql     # Creates the database schema
├── db.php           # Database connection
├── index.php        # Lists all students (main page)
├── create.php        # Form + handler for adding a student
├── edit.php          # Form + handler for editing a student
├── delete.php         # Handler for deleting a student
├── style.css          # Shared styling
└── README.md
```

## Database Schema

Table: `students` (database: `student_grades`)

| Column      | Type          | Notes             |
|-------------|---------------|--------------------|
| student_id  | INT           | Primary key         |
| first_name  | VARCHAR(50)   |                      |
| last_name   | VARCHAR(50)   |                      |
| course      | VARCHAR(50)   |                      |
| grade       | DECIMAL(3,2)  | Range: 0.00–9.99    |

## Setup

### 1. Create the database

In your MySQL client:

```sql
SOURCE database.sql;
```

or run the contents of `database.sql` manually — it creates the `student_grades` database and the `students` table.

### 2. Create a database user (recommended)

Rather than using `root`, create a dedicated app user:

```sql
CREATE USER 'grading_app'@'localhost' IDENTIFIED BY 'yourpassword';
GRANT ALL PRIVILEGES ON student_grades.* TO 'grading_app'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Configure the connection

Edit `db.php` and set your credentials:

```php
$host = "localhost";
$user = "grading_app";
$password = "yourpassword";
$dbname = "student_grades";
```

### 4. Start MySQL

On WSL/Linux, MySQL does not start automatically:

```bash
sudo service mysql start
```

### 5. Run the PHP dev server

```bash
php -S localhost:8000
```

Then open [http://localhost:8000](http://localhost:8000) in your browser.

## Usage

- **Home (`index.php`)** — shows all students with Edit/Delete links, plus a link to add a new one.
- **Add Student (`create.php`)** — fill out the form to insert a new record. Validates required fields and that the grade is numeric between 0 and 9.99.
- **Edit Student (`edit.php?id=...`)** — loads the student by ID and lets you update everything except the ID.
- **Delete Student (`delete.php?id=...`)** — deletes the record and redirects back to the list (prompts for confirmation first).

## Troubleshooting

**"Access denied for user 'root'@'localhost'"**
This is a MySQL auth issue, not a PHP bug. Common causes:
- MySQL isn't running (`sudo service mysql start`)
- `root` uses the `auth_socket` plugin, which blocks password logins from PHP — use a dedicated user instead (see Setup step 2)
- Wrong credentials in `db.php`

**Blank page / 500 error**
Check the terminal running `php -S` for the actual error — PHP prints stack traces there even though the browser shows a generic error.

## Possible Improvements

- Search/filter students by name or course
- Sort table by column
- Pagination for large datasets
- CSV export of grades
- Input sanitization/validation on the client side (JS) in addition to server side