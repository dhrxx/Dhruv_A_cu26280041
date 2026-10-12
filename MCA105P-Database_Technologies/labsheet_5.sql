-- MCA105P Database Technologies
-- Combined solution file: all questions for this lab sheet in one .sql file.
-- SQL dialect: MySQL / MariaDB
-- Note: Run in order. Some questions intentionally test constraint errors or alter/drop objects.

-- SETUP / SAMPLE DATA
-- Run this section first.

CREATE DATABASE IF NOT EXISTS CollegeJoinDB; USE CollegeJoinDB;
CREATE TABLE IF NOT EXISTS Departments(DeptID INT PRIMARY KEY, DeptName VARCHAR(50), Location VARCHAR(50));
CREATE TABLE IF NOT EXISTS Employees(EmpID INT PRIMARY KEY, EmpName VARCHAR(50), DeptID INT, Salary DECIMAL(10,2), City VARCHAR(50));
CREATE TABLE IF NOT EXISTS SalaryGrades(Grade CHAR(2) PRIMARY KEY, MinSalary DECIMAL(10,2), MaxSalary DECIMAL(10,2));
INSERT IGNORE INTO Departments VALUES(10,'Computer Science','Block A'),(20,'Pharmacy','Block B'),(30,'Management','Block C'),(40,'Civil Engineering','Block D');
INSERT IGNORE INTO Employees VALUES(101,'Aarav',10,65000,'Delhi'),(102,'Priya',20,72000,'Mumbai'),(103,'Rahul',10,48000,'Delhi'),(104,'Sneha',30,91000,'Pune'),(105,'Vikram',20,55000,'Jaipur'),(106,'Neha',50,80000,'Bengaluru'),(107,'Kabir',30,43000,'Pune');
INSERT IGNORE INTO SalaryGrades VALUES('G1',0,49999),('G2',50000,69999),('G3',70000,89999),('G4',90000,120000);
-- Verify all three tables with SELECT *.


----------------------------------------------------------------------------
-- Q01_basic_inner_join.sql
----------------------------------------------------------------------------

-- Basic Inner Join
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,d.DeptName,d.Location FROM Employees e INNER JOIN Departments d ON e.DeptID=d.DeptID;

-- Sample output / observation:
-- 6 matched employees; Neha (DeptID 50) is excluded.


----------------------------------------------------------------------------
-- Q02_employee_and_department_ids.sql
----------------------------------------------------------------------------

-- Employee and Department IDs
-- Run in the database specified in this lab sheet.
SELECT e.EmpID,e.EmpName,e.DeptID,d.DeptName FROM Employees e INNER JOIN Departments d ON e.DeptID=d.DeptID;

-- Sample output / observation:
-- 6 matched employees.


----------------------------------------------------------------------------
-- Q03_pharmacy_employees.sql
----------------------------------------------------------------------------

-- Pharmacy Employees
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,e.Salary FROM Employees e INNER JOIN Departments d ON e.DeptID=d.DeptID WHERE d.DeptName='Pharmacy';

-- Sample output / observation:
-- Priya 72000; Vikram 55000.


----------------------------------------------------------------------------
-- Q04_salary_filter.sql
----------------------------------------------------------------------------

-- Salary Filter
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,e.Salary,d.DeptName FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID WHERE e.Salary>60000;

-- Sample output / observation:
-- Aarav, Priya, Sneha.


----------------------------------------------------------------------------
-- Q05_equi_join.sql
----------------------------------------------------------------------------

-- Equi Join
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,d.DeptName FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID;

-- Sample output / observation:
-- Equality-based join; six rows.


----------------------------------------------------------------------------
-- Q06_equi_join_multiple_columns.sql
----------------------------------------------------------------------------

-- Equi Join Multiple Columns
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,e.City,d.DeptName,d.Location FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID;

-- Sample output / observation:
-- Six rows with employee city and department location.


----------------------------------------------------------------------------
-- Q07_computer_science.sql
----------------------------------------------------------------------------

-- Computer Science
-- Run in the database specified in this lab sheet.
SELECT e.EmpName FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID WHERE d.DeptName='Computer Science';

-- Sample output / observation:
-- Aarav and Rahul.


----------------------------------------------------------------------------
-- Q08_theta_join_greater_than.sql
----------------------------------------------------------------------------

-- Theta Join Greater Than
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,e.Salary,g.Grade FROM Employees e JOIN SalaryGrades g ON e.Salary>g.MinSalary;

-- Sample output / observation:
-- Range join returns every salary-grade pair where salary exceeds the minimum.


----------------------------------------------------------------------------
-- Q09_theta_join_salary_range.sql
----------------------------------------------------------------------------

-- Theta Join Salary Range
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,e.Salary,g.Grade FROM Employees e JOIN SalaryGrades g ON e.Salary BETWEEN g.MinSalary AND g.MaxSalary;

-- Sample output / observation:
-- Aarav G2, Priya G3, Rahul G1, Sneha G4, Vikram G2, Neha G3, Kabir G1.


----------------------------------------------------------------------------
-- Q10_theta_join_less_than.sql
----------------------------------------------------------------------------

-- Theta Join Less Than
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,g.Grade FROM Employees e JOIN SalaryGrades g ON e.Salary<g.MaxSalary;

-- Sample output / observation:
-- Returns salary-grade pairs below each grade maximum.


----------------------------------------------------------------------------
-- Q11_theta_join_greater_than_equal.sql
----------------------------------------------------------------------------

-- Theta Join Greater Than Equal
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,e.Salary,g.Grade FROM Employees e JOIN SalaryGrades g ON e.Salary>=g.MinSalary;

-- Sample output / observation:
-- Returns all pairs where salary meets grade minimum.


----------------------------------------------------------------------------
-- Q12_natural_join.sql
----------------------------------------------------------------------------

-- Natural Join
-- Run in the database specified in this lab sheet.
SELECT EmpName,DeptID,DeptName FROM Employees NATURAL JOIN Departments;

-- Sample output / observation:
-- Six matched employees.


----------------------------------------------------------------------------
-- Q13_natural_join_filter.sql
----------------------------------------------------------------------------

-- Natural Join Filter
-- Run in the database specified in this lab sheet.
SELECT EmpName,DeptName,Location FROM Employees NATURAL JOIN Departments WHERE Location='Block B';

-- Sample output / observation:
-- Priya and Vikram.


----------------------------------------------------------------------------
-- Q14_natural_join_salary.sql
----------------------------------------------------------------------------

-- Natural Join Salary
-- Run in the database specified in this lab sheet.
SELECT EmpName,DeptName,Salary FROM Employees NATURAL JOIN Departments WHERE Salary>=70000;

-- Sample output / observation:
-- Priya and Sneha.


----------------------------------------------------------------------------
-- Q15_inner_join_order_by.sql
----------------------------------------------------------------------------

-- Inner Join Order By
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,d.DeptName,e.Salary FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID ORDER BY e.Salary DESC;

-- Sample output / observation:
-- Highest first: Sneha, Priya, Aarav, Vikram, Rahul, Kabir.


----------------------------------------------------------------------------
-- Q16_distinct_departments.sql
----------------------------------------------------------------------------

-- Distinct Departments
-- Run in the database specified in this lab sheet.
SELECT DISTINCT d.DeptName FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID;

-- Sample output / observation:
-- Computer Science, Pharmacy, Management.


----------------------------------------------------------------------------
-- Q17_employee_count.sql
----------------------------------------------------------------------------

-- Employee Count
-- Run in the database specified in this lab sheet.
SELECT d.DeptName,COUNT(*) AS EmployeeCount FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID GROUP BY d.DeptName;

-- Sample output / observation:
-- Computer Science 2; Pharmacy 2; Management 2.


----------------------------------------------------------------------------
-- Q18_average_salary.sql
----------------------------------------------------------------------------

-- Average Salary
-- Run in the database specified in this lab sheet.
SELECT d.DeptName,AVG(e.Salary) AS AverageSalary FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID GROUP BY d.DeptName;

-- Sample output / observation:
-- CS 56500; Pharmacy 63500; Management 67000.


----------------------------------------------------------------------------
-- Q19_having_average_salary.sql
----------------------------------------------------------------------------

-- Having Average Salary
-- Run in the database specified in this lab sheet.
SELECT d.DeptName,AVG(e.Salary) AS AverageSalary FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID GROUP BY d.DeptName HAVING AVG(e.Salary)>60000;

-- Sample output / observation:
-- Pharmacy 63500; Management 67000.


----------------------------------------------------------------------------
-- Q20_highest_paid_employee.sql
----------------------------------------------------------------------------

-- Highest Paid Employee
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,d.DeptName,e.Salary FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID ORDER BY e.Salary DESC LIMIT 1;

-- Sample output / observation:
-- Sneha, Management, 91000.


----------------------------------------------------------------------------
-- Q21_in_condition.sql
----------------------------------------------------------------------------

-- IN Condition
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,d.DeptName FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID WHERE d.DeptName IN ('Computer Science','Pharmacy');

-- Sample output / observation:
-- Aarav, Rahul, Priya, Vikram.


----------------------------------------------------------------------------
-- Q22_multiple_where_conditions.sql
----------------------------------------------------------------------------

-- Multiple Where Conditions
-- Run in the database specified in this lab sheet.
SELECT e.EmpName,e.City,e.Salary,d.DeptName FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID WHERE e.City IN ('Delhi','Pune') AND e.Salary>=50000;

-- Sample output / observation:
-- Aarav and Sneha.


----------------------------------------------------------------------------
-- Q23_matched_rows_observation.sql
----------------------------------------------------------------------------

-- Matched Rows Observation
-- Run in the database specified in this lab sheet.
SELECT e.EmpID,e.EmpName,e.DeptID,d.DeptName FROM Employees e INNER JOIN Departments d ON e.DeptID=d.DeptID;

-- Sample output / observation:
-- Neha is excluded because DeptID 50 has no matching department.


----------------------------------------------------------------------------
-- Q24_natural_join_group_by.sql
----------------------------------------------------------------------------

-- Natural Join Group By
-- Run in the database specified in this lab sheet.
SELECT DeptName,COUNT(*) AS EmployeeCount FROM Employees NATURAL JOIN Departments GROUP BY DeptName;

-- Sample output / observation:
-- Computer Science 2; Pharmacy 2; Management 2.


----------------------------------------------------------------------------
-- Q25_final_challenge_total_salary.sql
----------------------------------------------------------------------------

-- Final Challenge Total Salary
-- Run in the database specified in this lab sheet.
SELECT d.DeptName,SUM(e.Salary) AS TotalSalary FROM Employees e JOIN Departments d ON e.DeptID=d.DeptID GROUP BY d.DeptName;

-- Sample output / observation:
-- Computer Science 113000; Pharmacy 127000; Management 134000.
