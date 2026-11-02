-- DBMS Lab: Indexing and Hashing
-- WARNING: resets IndexHashLab.
CREATE DATABASE IF NOT EXISTS IndexHashLab;
USE IndexHashLab;
DROP TABLE IF EXISTS Students_Hash;
DROP TABLE IF EXISTS Students_InnoDB;
DROP TABLE IF EXISTS Students_NoIndex;

-- Experiment 1: table with no explicit index.
CREATE TABLE Students_NoIndex (
    Student_ID INT NOT NULL,
    Student_Name VARCHAR(50),
    Department VARCHAR(30),
    City VARCHAR(30)
) ENGINE=InnoDB;
INSERT INTO Students_NoIndex VALUES
(101,'Ravi','MCA','Jaipur'), (102,'Priya','BCA','Delhi'),
(103,'Aman','MCA','Jaipur'), (104,'Neha','BCA','Mumbai'),
(105,'Karan','MCA','Delhi'), (106,'Simran','BCA','Jaipur'),
(107,'Arjun','MCA','Mumbai'), (108,'Meera','BCA','Delhi'),
(109,'Ishita','MCA','Pune'), (110,'Kabir','BCA','Pune');
EXPLAIN SELECT * FROM Students_NoIndex WHERE Student_ID = 103;
SELECT * FROM Students_NoIndex WHERE Student_ID = 103;

-- Experiment 2: add index and compare the plan.
CREATE INDEX idx_student_id ON Students_NoIndex(Student_ID);
SHOW INDEX FROM Students_NoIndex;
EXPLAIN SELECT * FROM Students_NoIndex WHERE Student_ID = 103;

-- Experiment 3: index a frequently filtered column.
CREATE INDEX idx_department ON Students_NoIndex(Department);
EXPLAIN SELECT * FROM Students_NoIndex WHERE Department = 'MCA';

-- Experiment 4: composite index and leftmost-prefix rule.
CREATE INDEX idx_department_city ON Students_NoIndex(Department, City);
EXPLAIN SELECT * FROM Students_NoIndex
WHERE Department = 'MCA' AND City = 'Jaipur';
EXPLAIN SELECT * FROM Students_NoIndex WHERE Department = 'MCA';
-- This index is generally not as useful for a predicate on City alone:
EXPLAIN SELECT * FROM Students_NoIndex WHERE City = 'Jaipur';

-- Experiment 5: explicit HASH index with MEMORY engine.
CREATE TABLE Students_Hash (
    Student_ID INT NOT NULL,
    Student_Name VARCHAR(50),
    Department VARCHAR(30),
    INDEX idx_hash_student_id (Student_ID) USING HASH
) ENGINE=MEMORY;
INSERT INTO Students_Hash VALUES
(101,'Ravi','MCA'), (102,'Priya','BCA'), (103,'Aman','MCA'),
(104,'Neha','BCA'), (105,'Karan','MCA');
SHOW INDEX FROM Students_Hash;
EXPLAIN SELECT * FROM Students_Hash WHERE Student_ID = 103;

-- Experiment 6: equality and range searches.
EXPLAIN SELECT * FROM Students_Hash WHERE Student_ID = 103;
EXPLAIN SELECT * FROM Students_Hash WHERE Student_ID BETWEEN 102 AND 104;
SELECT * FROM Students_Hash WHERE Student_ID BETWEEN 102 AND 104;

-- Experiment 7: ordinary InnoDB B-tree index.
CREATE TABLE Students_InnoDB (
    Student_ID INT PRIMARY KEY,
    Student_Name VARCHAR(50),
    Department VARCHAR(30)
) ENGINE=InnoDB;
INSERT INTO Students_InnoDB VALUES
(1,'Ravi','MCA'), (2,'Priya','BCA'), (3,'Aman','MCA');
CREATE INDEX idx_innodb_name ON Students_InnoDB(Student_Name);
SHOW INDEX FROM Students_InnoDB;
EXPLAIN SELECT * FROM Students_InnoDB WHERE Student_Name = 'Ravi';

-- MySQL does not support explicit USING HASH for ordinary InnoDB indexes.
-- Do not execute:
-- CREATE INDEX idx_hash_name ON Students_InnoDB(Student_Name) USING HASH;

-- Experiment 8: drop index and compare plans.
DROP INDEX idx_student_id ON Students_NoIndex;
EXPLAIN SELECT * FROM Students_NoIndex WHERE Student_ID = 103;
