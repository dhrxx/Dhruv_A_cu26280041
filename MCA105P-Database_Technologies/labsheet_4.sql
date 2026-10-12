-- MCA105P Database Technologies
-- Combined solution file: all questions for this lab sheet in one .sql file.
-- SQL dialect: MySQL / MariaDB
-- Note: Run in order. Some questions intentionally test constraint errors or alter/drop objects.

-- SETUP / SAMPLE DATA
-- Run this section first.

CREATE DATABASE IF NOT EXISTS sql_lab; USE sql_lab;
CREATE TABLE IF NOT EXISTS Department(DeptID INT PRIMARY KEY,DeptName VARCHAR(50) NOT NULL);
CREATE TABLE IF NOT EXISTS Student(StudentID INT PRIMARY KEY,Name VARCHAR(100) NOT NULL,Email VARCHAR(100),Age INT,DeptID INT,JoiningDate DATE);
CREATE TABLE IF NOT EXISTS Enrollment(EnrollmentID INT PRIMARY KEY,StudentID INT,CourseName VARCHAR(100));
INSERT IGNORE INTO Department VALUES(10,'Computer Science'),(20,'Information Technology'),(30,'Pharmacy'),(40,'Management');
INSERT IGNORE INTO Student VALUES(101,'Amit Sharma','amit@example.com',20,10,'2026-07-01'),(102,'Priya Singh','priya@example.com',21,20,'2026-07-03'),(103,'Rahul Verma','rahul@example.com',22,30,'2026-07-05'),(104,'Neha Gupta','neha@example.com',20,10,'2026-07-08'),(105,'Arjun Mehta','arjun@example.com',23,40,'2026-07-10');
INSERT IGNORE INTO Enrollment VALUES(1,101,'DBMS'),(2,102,'Operating Systems'),(3,103,'Pharmacology'),(4,104,'Python');
-- The sheet also includes separate Employee, Product, student-marks and Library exercises; use each question file independently.


----------------------------------------------------------------------------
-- Q01_identify_department_primary_key.sql
----------------------------------------------------------------------------

-- Identify Department Primary Key
-- Run in the database specified in this lab sheet.
SELECT 'DeptID' AS PrimaryKeyColumn, 'Uniquely identifies each department; cannot be NULL or duplicated' AS Reason;

-- Sample output / observation:
-- DeptID is the primary key.


----------------------------------------------------------------------------
-- Q02_create_department_and_insert_rows.sql
----------------------------------------------------------------------------

-- Create Department and Insert Rows
-- Run in the database specified in this lab sheet.
CREATE TABLE Department_Q2(DeptID INT PRIMARY KEY,DeptName VARCHAR(50) NOT NULL);
INSERT INTO Department_Q2 VALUES(10,'Computer Science'),(20,'Information Technology'),(30,'Pharmacy'),(40,'Management');
SELECT * FROM Department_Q2;

-- Sample output / observation:
-- 4 department rows.


----------------------------------------------------------------------------
-- Q03_create_student_and_insert_rows.sql
----------------------------------------------------------------------------

-- Create Student and Insert Rows
-- Run in the database specified in this lab sheet.
CREATE TABLE Student_Q3(StudentID INT PRIMARY KEY,Name VARCHAR(100) NOT NULL,Email VARCHAR(100),Age INT,DeptID INT,JoiningDate DATE);
INSERT INTO Student_Q3 VALUES(101,'Amit Sharma','amit@example.com',20,10,'2026-07-01'),(102,'Priya Singh','priya@example.com',21,20,'2026-07-03'),(103,'Rahul Verma','rahul@example.com',22,30,'2026-07-05'),(104,'Neha Gupta','neha@example.com',20,10,'2026-07-08'),(105,'Arjun Mehta','arjun@example.com',23,40,'2026-07-10');
SELECT * FROM Student_Q3;

-- Sample output / observation:
-- 5 student rows.


----------------------------------------------------------------------------
-- Q04_test_duplicate_student_primary_key.sql
----------------------------------------------------------------------------

-- Test Duplicate Student Primary Key
-- Run in the database specified in this lab sheet.
-- Expected error: duplicate primary key.
INSERT INTO Student VALUES(101,'Karan Shah','karan@example.com',21,20,'2026-08-01');

-- Sample output / observation:
-- Rejected because StudentID 101 already exists.


----------------------------------------------------------------------------
-- Q05_create_enrollment_and_insert_rows.sql
----------------------------------------------------------------------------

-- Create Enrollment and Insert Rows
-- Run in the database specified in this lab sheet.
CREATE TABLE Enrollment_Q5(EnrollmentID INT PRIMARY KEY,StudentID INT,CourseName VARCHAR(100));
INSERT INTO Enrollment_Q5 VALUES(1,101,'DBMS'),(2,102,'Operating Systems'),(3,103,'Pharmacology'),(4,104,'Python');
SELECT * FROM Enrollment_Q5;

-- Sample output / observation:
-- 4 enrollment rows.


----------------------------------------------------------------------------
-- Q06_test_duplicate_enrollment_key.sql
----------------------------------------------------------------------------

-- Test Duplicate Enrollment Key
-- Run in the database specified in this lab sheet.
-- Expected error: duplicate primary key.
INSERT INTO Enrollment VALUES(1,105,'Data Mining');

-- Sample output / observation:
-- Rejected because EnrollmentID 1 already exists.


----------------------------------------------------------------------------
-- Q07_add_student_department_foreign_key.sql
----------------------------------------------------------------------------

-- Add Student Department Foreign Key
-- Run in the database specified in this lab sheet.
ALTER TABLE Student ADD CONSTRAINT fk_student_department FOREIGN KEY(DeptID) REFERENCES Department(DeptID);

-- Sample output / observation:
-- Foreign key added if existing values all match Department.


----------------------------------------------------------------------------
-- Q08_add_enrollment_student_foreign_key.sql
----------------------------------------------------------------------------

-- Add Enrollment Student Foreign Key
-- Run in the database specified in this lab sheet.
ALTER TABLE Enrollment ADD CONSTRAINT fk_enrollment_student FOREIGN KEY(StudentID) REFERENCES Student(StudentID);

-- Sample output / observation:
-- Foreign key added if existing values all match Student.


----------------------------------------------------------------------------
-- Q09_test_invalid_department_foreign_key.sql
----------------------------------------------------------------------------

-- Test Invalid Department Foreign Key
-- Run in the database specified in this lab sheet.
-- Expected error because DeptID 99 does not exist.
INSERT INTO Student VALUES(106,'Riya Das','riya@example.com',21,99,'2026-08-01');

-- Sample output / observation:
-- Rejected by foreign-key constraint.


----------------------------------------------------------------------------
-- Q10_test_invalid_enrollment_foreign_key.sql
----------------------------------------------------------------------------

-- Test Invalid Enrollment Foreign Key
-- Run in the database specified in this lab sheet.
-- Expected error because StudentID 999 does not exist.
INSERT INTO Enrollment VALUES(5,999,'DBMS');

-- Sample output / observation:
-- Rejected by foreign-key constraint.


----------------------------------------------------------------------------
-- Q11_students_with_department_names.sql
----------------------------------------------------------------------------

-- Students With Department Names
-- Run in the database specified in this lab sheet.
SELECT s.StudentID,s.Name,d.DeptName FROM Student s INNER JOIN Department d ON s.DeptID=d.DeptID;

-- Sample output / observation:
-- 5 rows with matching department names.


----------------------------------------------------------------------------
-- Q12_enrollment_with_student_name.sql
----------------------------------------------------------------------------

-- Enrollment With Student Name
-- Run in the database specified in this lab sheet.
SELECT e.EnrollmentID,s.Name,e.CourseName FROM Enrollment e INNER JOIN Student s ON e.StudentID=s.StudentID;

-- Sample output / observation:
-- 4 rows.


----------------------------------------------------------------------------
-- Q13_not_null_department_name.sql
----------------------------------------------------------------------------

-- NOT NULL Department Name
-- Run in the database specified in this lab sheet.
CREATE TABLE Department2(DeptID INT PRIMARY KEY,DeptName VARCHAR(50) NOT NULL);
-- Expected error: DeptName cannot be NULL.
INSERT INTO Department2 VALUES(50,NULL);

-- Sample output / observation:
-- Insert rejected by NOT NULL.


----------------------------------------------------------------------------
-- Q14_student_name_required.sql
----------------------------------------------------------------------------

-- Student Name Required
-- Run in the database specified in this lab sheet.
-- Expected error because Name is omitted and has NOT NULL.
INSERT INTO Student(StudentID,Email,Age,DeptID) VALUES(106,'riya@example.com',21,20);

-- Sample output / observation:
-- Insert rejected because Name is required.


----------------------------------------------------------------------------
-- Q15_unique_student_email.sql
----------------------------------------------------------------------------

-- Unique Student Email
-- Run in the database specified in this lab sheet.
ALTER TABLE Student ADD CONSTRAINT uq_student_email UNIQUE(Email);
-- Expected duplicate-email error.
INSERT INTO Student VALUES(106,'Riya Das','amit@example.com',21,20,'2026-08-01');

-- Sample output / observation:
-- Duplicate email rejected.


----------------------------------------------------------------------------
-- Q16_create_library_with_unique_book_id.sql
----------------------------------------------------------------------------

-- Create Library With Unique Book ID
-- Run in the database specified in this lab sheet.
CREATE TABLE Library(BookID INT UNIQUE,Title VARCHAR(150) NOT NULL);
INSERT INTO Library VALUES(1,'Database Systems'),(2,'Operating Systems'),(3,'Computer Networks'),(4,'Python Programming'),(5,'Data Mining');
SELECT * FROM Library;

-- Sample output / observation:
-- 5 books.


----------------------------------------------------------------------------
-- Q17_check_student_age_greater_than_18.sql
----------------------------------------------------------------------------

-- Check Student Age Greater Than 18
-- Run in the database specified in this lab sheet.
ALTER TABLE Student ADD CONSTRAINT chk_student_age CHECK(Age>18);
-- Expected CHECK constraint error.
INSERT INTO Student VALUES(106,'Riya Das','riya@example.com',17,20,'2026-08-01');

-- Sample output / observation:
-- Age 17 rejected.


----------------------------------------------------------------------------
-- Q18_course_credits_between_1_and_6.sql
----------------------------------------------------------------------------

-- Course Credits Between 1 and 6
-- Run in the database specified in this lab sheet.
CREATE TABLE Course_Q18(CourseID INT PRIMARY KEY,CourseName VARCHAR(100),Credits INT CHECK(Credits BETWEEN 1 AND 6));
-- Expected CHECK error.
INSERT INTO Course_Q18 VALUES(1,'DBMS',8);

-- Sample output / observation:
-- Credits 8 rejected.


----------------------------------------------------------------------------
-- Q19_default_joining_date.sql
----------------------------------------------------------------------------

-- Default Joining Date
-- Run in the database specified in this lab sheet.
ALTER TABLE Student ADD COLUMN JoiningDate2 DATE DEFAULT (CURRENT_DATE);
INSERT INTO Student(StudentID,Name,Email,Age,DeptID) VALUES(106,'Riya Das','riya@example.com',21,20);
SELECT StudentID,Name,JoiningDate2 FROM Student WHERE StudentID=106;

-- Sample output / observation:
-- JoiningDate2 defaults to current date.


----------------------------------------------------------------------------
-- Q20_faculty_default_designation.sql
----------------------------------------------------------------------------

-- Faculty Default Designation
-- Run in the database specified in this lab sheet.
CREATE TABLE Faculty(FacultyID INT PRIMARY KEY,FacultyName VARCHAR(100) NOT NULL,Designation VARCHAR(50) DEFAULT 'Lecturer');
INSERT INTO Faculty(FacultyID,FacultyName) VALUES(1,'Dr. Meera Sharma');
SELECT * FROM Faculty;

-- Sample output / observation:
-- Designation = Lecturer.


----------------------------------------------------------------------------
-- Q21_create_employees_table.sql
----------------------------------------------------------------------------

-- Create Employees Table
-- Run in the database specified in this lab sheet.
CREATE TABLE Employees(emp_id INT PRIMARY KEY,name VARCHAR(100),department VARCHAR(50),salary DECIMAL(10,2),hire_date DATE);

-- Sample output / observation:
-- Employees table created.


----------------------------------------------------------------------------
-- Q22_insert_one_employee.sql
----------------------------------------------------------------------------

-- Insert One Employee
-- Run in the database specified in this lab sheet.
INSERT INTO Employees VALUES(1,'John Smith','IT',55000,'2024-01-15');

-- Sample output / observation:
-- One row inserted.


----------------------------------------------------------------------------
-- Q23_insert_multiple_employees.sql
----------------------------------------------------------------------------

-- Insert Multiple Employees
-- Run in the database specified in this lab sheet.
INSERT INTO Employees VALUES(2,'Lisa Wilson','HR',45000,'2024-02-10'),(3,'Mark Lee','Finance',62000,'2024-01-20'),(4,'Sara Khan','IT',51000,'2024-01-05');

-- Sample output / observation:
-- Three rows inserted.


----------------------------------------------------------------------------
-- Q24_insert_partial_employee.sql
----------------------------------------------------------------------------

-- Insert Partial Employee
-- Run in the database specified in this lab sheet.
INSERT INTO Employees(emp_id,name,department) VALUES(5,'Ravi Das','Finance');

-- Sample output / observation:
-- Salary and hire_date are NULL.


----------------------------------------------------------------------------
-- Q25_display_all_employees.sql
----------------------------------------------------------------------------

-- Display All Employees
-- Run in the database specified in this lab sheet.
SELECT * FROM Employees;

-- Sample output / observation:
-- Displays all employee records.


----------------------------------------------------------------------------
-- Q26_display_all_employees_again.sql
----------------------------------------------------------------------------

-- Display All Employees Again
-- Run in the database specified in this lab sheet.
SELECT * FROM Employees;

-- Sample output / observation:
-- Displays all employee records.


----------------------------------------------------------------------------
-- Q27_display_employee_names_and_salaries.sql
----------------------------------------------------------------------------

-- Display Employee Names and Salaries
-- Run in the database specified in this lab sheet.
SELECT name,salary FROM Employees;

-- Sample output / observation:
-- Two columns returned.


----------------------------------------------------------------------------
-- Q28_display_it_employees.sql
----------------------------------------------------------------------------

-- Display IT Employees
-- Run in the database specified in this lab sheet.
SELECT * FROM Employees WHERE department='IT';

-- Sample output / observation:
-- IT employees only.


----------------------------------------------------------------------------
-- Q29_salary_above_50000.sql
----------------------------------------------------------------------------

-- Salary Above 50000
-- Run in the database specified in this lab sheet.
SELECT * FROM Employees WHERE salary>50000;

-- Sample output / observation:
-- Employees earning above 50000.


----------------------------------------------------------------------------
-- Q30_employees_hired_january_2024.sql
----------------------------------------------------------------------------

-- Employees Hired January 2024
-- Run in the database specified in this lab sheet.
SELECT * FROM Employees WHERE hire_date>='2024-01-01' AND hire_date<'2024-02-01';

-- Sample output / observation:
-- Employees hired in January 2024.


----------------------------------------------------------------------------
-- Q31_distinct_departments.sql
----------------------------------------------------------------------------

-- Distinct Departments
-- Run in the database specified in this lab sheet.
SELECT DISTINCT department FROM Employees;

-- Sample output / observation:
-- Unique department names.


----------------------------------------------------------------------------
-- Q32_count_employees.sql
----------------------------------------------------------------------------

-- Count Employees
-- Run in the database specified in this lab sheet.
SELECT COUNT(*) AS TotalEmployees FROM Employees;

-- Sample output / observation:
-- Total count.


----------------------------------------------------------------------------
-- Q33_salary_statistics.sql
----------------------------------------------------------------------------

-- Salary Statistics
-- Run in the database specified in this lab sheet.
SELECT MAX(salary) AS MaxSalary,MIN(salary) AS MinSalary,AVG(salary) AS AvgSalary FROM Employees;

-- Sample output / observation:
-- Maximum, minimum and average salary.


----------------------------------------------------------------------------
-- Q34_update_john_smith_salary.sql
----------------------------------------------------------------------------

-- Update John Smith Salary
-- Run in the database specified in this lab sheet.
UPDATE Employees SET salary=58000 WHERE name='John Smith';
SELECT * FROM Employees WHERE name='John Smith';

-- Sample output / observation:
-- John Smith salary 58000.


----------------------------------------------------------------------------
-- Q35_increase_hr_salary_5.sql
----------------------------------------------------------------------------

-- Increase HR Salary 5%
-- Run in the database specified in this lab sheet.
UPDATE Employees SET salary=salary*1.05 WHERE department='HR';
SELECT * FROM Employees WHERE department='HR';

-- Sample output / observation:
-- HR salaries increased by 5%.


----------------------------------------------------------------------------
-- Q36_update_lisa_wilson.sql
----------------------------------------------------------------------------

-- Update Lisa Wilson
-- Run in the database specified in this lab sheet.
UPDATE Employees SET salary=47000,hire_date='2024-03-05' WHERE name='Lisa Wilson';
SELECT * FROM Employees WHERE name='Lisa Wilson';

-- Sample output / observation:
-- Lisa Wilson updated.


----------------------------------------------------------------------------
-- Q37_move_low_salary_to_operations.sql
----------------------------------------------------------------------------

-- Move Low Salary to Operations
-- Run in the database specified in this lab sheet.
UPDATE Employees SET department='Operations' WHERE salary<50000;

-- Sample output / observation:
-- Employees below 50000 moved to Operations.


----------------------------------------------------------------------------
-- Q38_add_2000_to_finance.sql
----------------------------------------------------------------------------

-- Add 2000 to Finance
-- Run in the database specified in this lab sheet.
UPDATE Employees SET salary=salary+2000 WHERE department='Finance';

-- Sample output / observation:
-- Finance salaries increased by 2000.


----------------------------------------------------------------------------
-- Q39_change_department_for_older_hires.sql
----------------------------------------------------------------------------

-- Change Department for Older Hires
-- Run in the database specified in this lab sheet.
UPDATE Employees SET department='Senior Staff' WHERE hire_date<'2024-02-01';

-- Sample output / observation:
-- Employees hired before Feb 1 moved.


----------------------------------------------------------------------------
-- Q40_delete_products_with_zero_stock.sql
----------------------------------------------------------------------------

-- Delete Products With Zero Stock
-- Run in the database specified in this lab sheet.
-- Assumes Products(product_name, stock) exists.
DELETE FROM Products WHERE stock=0;

-- Sample output / observation:
-- Zero-stock products deleted.


----------------------------------------------------------------------------
-- Q41_delete_expensive_electronics.sql
----------------------------------------------------------------------------

-- Delete Expensive Electronics
-- Run in the database specified in this lab sheet.
-- Assumes Products(category, price) exists.
DELETE FROM Products WHERE category='Electronics' AND price>1000;

-- Sample output / observation:
-- Electronics above 1000 deleted.


----------------------------------------------------------------------------
-- Q42_delete_desk_chair.sql
----------------------------------------------------------------------------

-- Delete Desk Chair
-- Run in the database specified in this lab sheet.
-- Assumes Products(product_name) exists.
DELETE FROM Products WHERE product_name='Desk Chair';

-- Sample output / observation:
-- Desk Chair deleted if present.


----------------------------------------------------------------------------
-- Q43_delete_items_under_100.sql
----------------------------------------------------------------------------

-- Delete Items Under 100
-- Run in the database specified in this lab sheet.
-- Assumes Products(price) exists.
DELETE FROM Products WHERE price<100;

-- Sample output / observation:
-- Items under 100 deleted.


----------------------------------------------------------------------------
-- Q44_students_sorted_by_marks.sql
----------------------------------------------------------------------------

-- Students Sorted By Marks
-- Run in the database specified in this lab sheet.
SELECT StudentID,Name,Marks FROM StudentMarks ORDER BY Marks DESC;

-- Sample output / observation:
-- Student marks highest to lowest; create StudentMarks if needed.


----------------------------------------------------------------------------
-- Q45_top_five_scorers.sql
----------------------------------------------------------------------------

-- Top Five Scorers
-- Run in the database specified in this lab sheet.
SELECT StudentID,Name,Marks FROM StudentMarks ORDER BY Marks DESC LIMIT 5;

-- Sample output / observation:
-- Five highest marks.


----------------------------------------------------------------------------
-- Q46_computer_science_students_alphabetically.sql
----------------------------------------------------------------------------

-- Computer Science Students Alphabetically
-- Run in the database specified in this lab sheet.
SELECT Name FROM StudentMarks WHERE Department='Computer Science' ORDER BY Name;

-- Sample output / observation:
-- Computer Science students sorted by name.


----------------------------------------------------------------------------
-- Q47_bottom_three_scorers.sql
----------------------------------------------------------------------------

-- Bottom Three Scorers
-- Run in the database specified in this lab sheet.
SELECT StudentID,Name,Marks FROM StudentMarks ORDER BY Marks ASC LIMIT 3;

-- Sample output / observation:
-- Three lowest marks.


----------------------------------------------------------------------------
-- Q48_marks_between_80_and_90.sql
----------------------------------------------------------------------------

-- Marks Between 80 and 90
-- Run in the database specified in this lab sheet.
SELECT StudentID,Name,Marks FROM StudentMarks WHERE Marks BETWEEN 80 AND 90;

-- Sample output / observation:
-- Students with marks in inclusive range.


----------------------------------------------------------------------------
-- Q49_names_starting_with_a.sql
----------------------------------------------------------------------------

-- Names Starting With A
-- Run in the database specified in this lab sheet.
SELECT * FROM StudentMarks WHERE Name LIKE 'A%';

-- Sample output / observation:
-- Names beginning with A.


----------------------------------------------------------------------------
-- Q50_students_from_mumbai_or_delhi.sql
----------------------------------------------------------------------------

-- Students From Mumbai or Delhi
-- Run in the database specified in this lab sheet.
SELECT * FROM StudentMarks WHERE City IN ('Mumbai','Delhi');

-- Sample output / observation:
-- Students whose city is Mumbai or Delhi.


----------------------------------------------------------------------------
-- Q51_create_books_table.sql
----------------------------------------------------------------------------

-- Create Books Table
-- Run in the database specified in this lab sheet.
CREATE TABLE Books(book_id INT PRIMARY KEY,title VARCHAR(150) NOT NULL,author VARCHAR(100),isbn VARCHAR(30) UNIQUE);

-- Sample output / observation:
-- Books table created.


----------------------------------------------------------------------------
-- Q52_create_members_table.sql
----------------------------------------------------------------------------

-- Create Members Table
-- Run in the database specified in this lab sheet.
CREATE TABLE Members(member_id INT PRIMARY KEY,name VARCHAR(100) NOT NULL,email VARCHAR(100) UNIQUE);

-- Sample output / observation:
-- Members table created.


----------------------------------------------------------------------------
-- Q53_create_loans_table.sql
----------------------------------------------------------------------------

-- Create Loans Table
-- Run in the database specified in this lab sheet.
CREATE TABLE Loans(loan_id INT PRIMARY KEY,book_id INT,member_id INT,loan_date DATE,FOREIGN KEY(book_id) REFERENCES Books(book_id),FOREIGN KEY(member_id) REFERENCES Members(member_id));

-- Sample output / observation:
-- Loans table created.


----------------------------------------------------------------------------
-- Q54_insert_books_members_loans.sql
----------------------------------------------------------------------------

-- Insert Books Members Loans
-- Run in the database specified in this lab sheet.
INSERT INTO Books VALUES(1,'DBMS','Ramakrishnan','ISBN001'),(2,'Python Basics','Guido','ISBN002'),(3,'Networks','Tanenbaum','ISBN003');
INSERT INTO Members VALUES(1,'Amit','amit@example.com'),(2,'Priya','priya@example.com'),(3,'Rahul','rahul@example.com');
INSERT INTO Loans VALUES(1,1,1,CURRENT_DATE),(2,2,2,CURRENT_DATE),(3,3,3,CURRENT_DATE);

-- Sample output / observation:
-- 3 books, 3 members, 3 loans.


----------------------------------------------------------------------------
-- Q55_loan_details_with_book_and_member.sql
----------------------------------------------------------------------------

-- Loan Details With Book and Member
-- Run in the database specified in this lab sheet.
SELECT l.loan_id,b.title,m.name AS member_name,l.loan_date FROM Loans l JOIN Books b ON l.book_id=b.book_id JOIN Members m ON l.member_id=m.member_id;

-- Sample output / observation:
-- Three loan rows with title and member name.
