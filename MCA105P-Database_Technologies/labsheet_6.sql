-- MCA105P Database Technologies
-- Combined solution file: all questions for this lab sheet in one .sql file.
-- SQL dialect: MySQL / MariaDB
-- Note: Run in order. Some questions intentionally test constraint errors or alter/drop objects.

-- SETUP / SAMPLE DATA
-- Run this section first.

CREATE DATABASE IF NOT EXISTS CollegeDB; USE CollegeDB;
CREATE TABLE IF NOT EXISTS Department(DeptID INT PRIMARY KEY, DeptName VARCHAR(50) NOT NULL, Location VARCHAR(50));
CREATE TABLE IF NOT EXISTS Student(StudentID INT PRIMARY KEY, StudentName VARCHAR(50) NOT NULL, Age INT, Gender VARCHAR(10), Marks DECIMAL(5,2), DeptID INT, Email VARCHAR(100), FOREIGN KEY(DeptID) REFERENCES Department(DeptID));
CREATE TABLE IF NOT EXISTS Course(CourseID INT PRIMARY KEY, CourseName VARCHAR(50) NOT NULL, Credits INT);
INSERT IGNORE INTO Department VALUES (101,'Computer Science','Block A'),(102,'Pharmacy','Block B'),(103,'Commerce','Block C'),(104,'Mathematics','Block D');
INSERT IGNORE INTO Student VALUES (1,'Amit',20,'Male',85.50,101,'amit@gmail.com'),(2,'Priya',21,'Female',92,102,'priya@gmail.com'),(3,'Rahul',19,'Male',76.50,101,'rahul@gmail.com'),(4,'Neha',22,'Female',88,103,'neha@gmail.com'),(5,'Arjun',20,'Male',69.50,104,'arjun@gmail.com'),(6,'Sneha',21,'Female',95,102,'sneha@gmail.com'),(7,'Rohit',23,'Male',72,103,'rohit@gmail.com'),(8,'Pooja',20,'Female',81,104,'pooja@gmail.com');
INSERT IGNORE INTO Course VALUES(201,'Database Management System',4),(202,'Python Programming',4),(203,'Mathematics',3),(204,'Computer Networks',3),(205,'Data Structures',4);
-- Verify: SELECT * FROM Department; SELECT * FROM Student; SELECT * FROM Course;


----------------------------------------------------------------------------
-- Q01_procedure_showstudents.sql
----------------------------------------------------------------------------

-- Procedure ShowStudents
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS ShowStudents;
DELIMITER //
CREATE PROCEDURE ShowStudents() BEGIN SELECT * FROM Student; END //
DELIMITER ;
CALL ShowStudents();;

-- Sample output / observation:
-- All 8 student rows.


----------------------------------------------------------------------------
-- Q02_procedure_showdepartments.sql
----------------------------------------------------------------------------

-- Procedure ShowDepartments
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS ShowDepartments;
DELIMITER //
CREATE PROCEDURE ShowDepartments() BEGIN SELECT * FROM Department; END //
DELIMITER ;
CALL ShowDepartments();;

-- Sample output / observation:
-- 4 department rows.


----------------------------------------------------------------------------
-- Q03_procedure_showcourses.sql
----------------------------------------------------------------------------

-- Procedure ShowCourses
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS ShowCourses;
DELIMITER //
CREATE PROCEDURE ShowCourses() BEGIN SELECT * FROM Course; END //
DELIMITER ;
CALL ShowCourses();;

-- Sample output / observation:
-- 5 course rows.


----------------------------------------------------------------------------
-- Q04_procedure_getstudent.sql
----------------------------------------------------------------------------

-- Procedure GetStudent
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS GetStudent;
DELIMITER //
CREATE PROCEDURE GetStudent(IN p_id INT) BEGIN SELECT * FROM Student WHERE StudentID=p_id; END //
DELIMITER ;
CALL GetStudent(3);;

-- Sample output / observation:
-- Rahul, StudentID 3.


----------------------------------------------------------------------------
-- Q05_procedure_getstudentsbydept.sql
----------------------------------------------------------------------------

-- Procedure GetStudentsByDept
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS GetStudentsByDept;
DELIMITER //
CREATE PROCEDURE GetStudentsByDept(IN p_dept INT) BEGIN SELECT * FROM Student WHERE DeptID=p_dept; END //
DELIMITER ;
CALL GetStudentsByDept(102);;

-- Sample output / observation:
-- Priya and Sneha.


----------------------------------------------------------------------------
-- Q06_procedure_studentsabovemarks.sql
----------------------------------------------------------------------------

-- Procedure StudentsAboveMarks
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS StudentsAboveMarks;
DELIMITER //
CREATE PROCEDURE StudentsAboveMarks(IN p_marks DECIMAL(5,2)) BEGIN SELECT * FROM Student WHERE Marks>p_marks; END //
DELIMITER ;
CALL StudentsAboveMarks(80);;

-- Sample output / observation:
-- Amit, Priya, Neha, Sneha and Pooja.


----------------------------------------------------------------------------
-- Q07_procedure_totalstudents.sql
----------------------------------------------------------------------------

-- Procedure TotalStudents
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS TotalStudents;
DELIMITER //
CREATE PROCEDURE TotalStudents() BEGIN SELECT COUNT(*) AS TotalStudents FROM Student; END //
DELIMITER ;
CALL TotalStudents();;

-- Sample output / observation:
-- TotalStudents = 8.


----------------------------------------------------------------------------
-- Q08_procedure_checkresult.sql
----------------------------------------------------------------------------

-- Procedure CheckResult
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS CheckResult;
DELIMITER //
CREATE PROCEDURE CheckResult(IN p_id INT) BEGIN SELECT StudentID,StudentName,IF(Marks>=50,'Pass','Fail') AS Result FROM Student WHERE StudentID=p_id; END //
DELIMITER ;
CALL CheckResult(3);;

-- Sample output / observation:
-- Rahul — Pass.


----------------------------------------------------------------------------
-- Q09_procedure_getstudentsbygender.sql
----------------------------------------------------------------------------

-- Procedure GetStudentsByGender
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS GetStudentsByGender;
DELIMITER //
CREATE PROCEDURE GetStudentsByGender(IN p_gender VARCHAR(10)) BEGIN SELECT * FROM Student WHERE Gender=p_gender; END //
DELIMITER ;
CALL GetStudentsByGender('Female');;

-- Sample output / observation:
-- Priya, Neha, Sneha and Pooja.


----------------------------------------------------------------------------
-- Q10_procedure_studentsbetweenmarks.sql
----------------------------------------------------------------------------

-- Procedure StudentsBetweenMarks
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS StudentsBetweenMarks;
DELIMITER //
CREATE PROCEDURE StudentsBetweenMarks(IN p_low DECIMAL(5,2), IN p_high DECIMAL(5,2)) BEGIN SELECT * FROM Student WHERE Marks BETWEEN p_low AND p_high; END //
DELIMITER ;
CALL StudentsBetweenMarks(70,90);;

-- Sample output / observation:
-- Amit, Rahul, Neha, Rohit and Pooja.


----------------------------------------------------------------------------
-- Q11_function_addtwonumbers.sql
----------------------------------------------------------------------------

-- Function AddTwoNumbers
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS AddTwoNumbers;
DELIMITER //
CREATE FUNCTION AddTwoNumbers(a INT,b INT) RETURNS INT DETERMINISTIC RETURN a+b //
DELIMITER ;
SELECT AddTwoNumbers(10,20) AS Result;;

-- Sample output / observation:
-- 30


----------------------------------------------------------------------------
-- Q12_function_findsquare.sql
----------------------------------------------------------------------------

-- Function FindSquare
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS FindSquare;
DELIMITER //
CREATE FUNCTION FindSquare(n INT) RETURNS INT DETERMINISTIC RETURN n*n //
DELIMITER ;
SELECT FindSquare(5) AS Square;;

-- Sample output / observation:
-- 25


----------------------------------------------------------------------------
-- Q13_function_resultstatus.sql
----------------------------------------------------------------------------

-- Function ResultStatus
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS ResultStatus;
DELIMITER //
CREATE FUNCTION ResultStatus(m DECIMAL(5,2)) RETURNS VARCHAR(10) DETERMINISTIC RETURN IF(m>=50,'Pass','Fail') //
DELIMITER ;
SELECT ResultStatus(76) AS Result;;

-- Sample output / observation:
-- Pass


----------------------------------------------------------------------------
-- Q14_function_studentgrade.sql
----------------------------------------------------------------------------

-- Function StudentGrade
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS StudentGrade;
DELIMITER //
CREATE FUNCTION StudentGrade(m DECIMAL(5,2)) RETURNS CHAR(1) DETERMINISTIC RETURN CASE WHEN m>=90 THEN 'A' WHEN m>=75 THEN 'B' WHEN m>=60 THEN 'C' ELSE 'D' END //
DELIMITER ;
SELECT StudentGrade(85) AS Grade;;

-- Sample output / observation:
-- B


----------------------------------------------------------------------------
-- Q15_function_agecategory.sql
----------------------------------------------------------------------------

-- Function AgeCategory
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS AgeCategory;
DELIMITER //
CREATE FUNCTION AgeCategory(a INT) RETURNS VARCHAR(10) DETERMINISTIC RETURN IF(a>=18,'Adult','Minor') //
DELIMITER ;
SELECT AgeCategory(20) AS Category;;

-- Sample output / observation:
-- Adult


----------------------------------------------------------------------------
-- Q16_function_calculatebonus.sql
----------------------------------------------------------------------------

-- Function CalculateBonus
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS CalculateBonus;
DELIMITER //
CREATE FUNCTION CalculateBonus(m DECIMAL(5,2)) RETURNS INT DETERMINISTIC RETURN CASE WHEN m>=90 THEN 1000 WHEN m>=75 THEN 500 ELSE 200 END //
DELIMITER ;
SELECT CalculateBonus(85) AS Bonus;;

-- Sample output / observation:
-- 500


----------------------------------------------------------------------------
-- Q17_function_highernumber.sql
----------------------------------------------------------------------------

-- Function HigherNumber
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS HigherNumber;
DELIMITER //
CREATE FUNCTION HigherNumber(a INT,b INT) RETURNS INT DETERMINISTIC RETURN GREATEST(a,b) //
DELIMITER ;
SELECT HigherNumber(12,20) AS Higher;;

-- Sample output / observation:
-- 20


----------------------------------------------------------------------------
-- Q18_function_doublemarks.sql
----------------------------------------------------------------------------

-- Function DoubleMarks
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS DoubleMarks;
DELIMITER //
CREATE FUNCTION DoubleMarks(m DECIMAL(5,2)) RETURNS DECIMAL(6,2) DETERMINISTIC RETURN m*2 //
DELIMITER ;
SELECT DoubleMarks(42.5) AS DoubleMarks;;

-- Sample output / observation:
-- 85.00


----------------------------------------------------------------------------
-- Q19_function_ageafterfiveyears.sql
----------------------------------------------------------------------------

-- Function AgeAfterFiveYears
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS AgeAfterFiveYears;
DELIMITER //
CREATE FUNCTION AgeAfterFiveYears(a INT) RETURNS INT DETERMINISTIC RETURN a+5 //
DELIMITER ;
SELECT AgeAfterFiveYears(20) AS AgeInFiveYears;;

-- Sample output / observation:
-- 25


----------------------------------------------------------------------------
-- Q20_display_student_name_marks_and_grade.sql
----------------------------------------------------------------------------

-- Display Student Name Marks and Grade
-- Run in the database specified in this lab sheet.
SELECT StudentName, Marks, StudentGrade(Marks) AS Grade FROM Student ORDER BY StudentID;

-- Sample output / observation:
-- Amit B, Priya A, Rahul B, Neha B, Arjun C, Sneha A, Rohit C, Pooja B.
