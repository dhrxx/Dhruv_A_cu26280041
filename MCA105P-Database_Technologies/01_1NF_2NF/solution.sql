-- DBMS Lab: 1NF and 2NF
-- WARNING: resets the NormalizationLab database.
CREATE DATABASE IF NOT EXISTS NormalizationLab;
USE NormalizationLab;
DROP TABLE IF EXISTS Enrollments;
DROP TABLE IF EXISTS Courses;
DROP TABLE IF EXISTS Students;
DROP TABLE IF EXISTS Student_Course_1NF;
DROP TABLE IF EXISTS Student_Course_UNF;

-- Program 1: UNF example (multiple courses/faculty values in one cell).
CREATE TABLE Student_Course_UNF (
    Student_ID INT,
    Student_Name VARCHAR(50),
    Courses VARCHAR(100),
    Faculty VARCHAR(100)
);
INSERT INTO Student_Course_UNF VALUES
(101, 'Ravi', 'DBMS, Java', 'Sharma, Verma'),
(102, 'Priya', 'DBMS', 'Sharma'),
(103, 'Aman', 'Java, Python', 'Verma, Khan');
SELECT * FROM Student_Course_UNF;

-- Program 2: 1NF — each cell contains one atomic value.
CREATE TABLE Student_Course_1NF (
    Student_ID INT,
    Student_Name VARCHAR(50),
    Course_ID VARCHAR(10),
    Course_Name VARCHAR(50),
    Faculty_Name VARCHAR(50),
    PRIMARY KEY (Student_ID, Course_ID)
);
INSERT INTO Student_Course_1NF VALUES
(101, 'Ravi', 'C01', 'DBMS', 'Sharma'),
(101, 'Ravi', 'C02', 'Java', 'Verma'),
(102, 'Priya', 'C01', 'DBMS', 'Sharma'),
(103, 'Aman', 'C02', 'Java', 'Verma'),
(103, 'Aman', 'C03', 'Python', 'Khan');
SELECT * FROM Student_Course_1NF;

-- Program 3: 2NF decomposition.
-- Student_ID -> Student_Name; Course_ID -> Course_Name, Faculty_Name.
-- The composite-key table stores only the enrollment relationship.
CREATE TABLE Students (
    Student_ID INT PRIMARY KEY,
    Student_Name VARCHAR(50) NOT NULL
);
CREATE TABLE Courses (
    Course_ID VARCHAR(10) PRIMARY KEY,
    Course_Name VARCHAR(50) NOT NULL,
    Faculty_Name VARCHAR(50) NOT NULL
);
CREATE TABLE Enrollments (
    Student_ID INT,
    Course_ID VARCHAR(10),
    PRIMARY KEY (Student_ID, Course_ID),
    FOREIGN KEY (Student_ID) REFERENCES Students(Student_ID),
    FOREIGN KEY (Course_ID) REFERENCES Courses(Course_ID)
);

-- Program 4: populate decomposed tables.
INSERT INTO Students VALUES
(101, 'Ravi'), (102, 'Priya'), (103, 'Aman');
INSERT INTO Courses VALUES
('C01', 'DBMS', 'Sharma'),
('C02', 'Java', 'Verma'),
('C03', 'Python', 'Khan');
INSERT INTO Enrollments VALUES
(101, 'C01'), (101, 'C02'), (102, 'C01'),
(103, 'C02'), (103, 'C03');

-- Program 5: display each table.
SELECT * FROM Students;
SELECT * FROM Courses;
SELECT * FROM Enrollments;

-- Program 6: retrieve complete enrollment details with JOIN.
SELECT s.Student_ID, s.Student_Name, c.Course_ID, c.Course_Name, c.Faculty_Name
FROM Enrollments e
JOIN Students s ON s.Student_ID = e.Student_ID
JOIN Courses c ON c.Course_ID = e.Course_ID
ORDER BY s.Student_ID, c.Course_ID;

-- Program 7: independent insertion is now possible.
INSERT INTO Students VALUES (104, 'Neha'); -- student with no enrollment
INSERT INTO Courses VALUES ('C04', 'Python Lab', 'Mehta'); -- course with no enrollment
SELECT * FROM Students WHERE Student_ID = 104;
SELECT * FROM Courses WHERE Course_ID = 'C04';

-- Program 8: update faculty for Java in one place.
UPDATE Courses SET Faculty_Name = 'Kapoor' WHERE Course_ID = 'C02';
SELECT * FROM Courses WHERE Course_ID = 'C02';

-- Program 9: delete one enrollment without deleting the student/course.
DELETE FROM Enrollments WHERE Student_ID = 103 AND Course_ID = 'C03';
SELECT * FROM Students;
SELECT * FROM Courses;
SELECT * FROM Enrollments;
