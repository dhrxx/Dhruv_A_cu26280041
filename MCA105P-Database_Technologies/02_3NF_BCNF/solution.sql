-- DBMS Lab: Third Normal Form and BCNF
-- WARNING: resets the Normalization3NF_BCNF database.
CREATE DATABASE IF NOT EXISTS Normalization3NF_BCNF;
USE Normalization3NF_BCNF;
DROP TABLE IF EXISTS Student_Instructor;
DROP TABLE IF EXISTS Instructor_Subject;
DROP TABLE IF EXISTS Courses_3NF;
DROP TABLE IF EXISTS Faculty;
DROP TABLE IF EXISTS Courses_2NF;

-- Program 1: 2NF starting point with transitive dependency:
-- Course_ID -> Faculty_ID -> Faculty_Name.
CREATE TABLE Courses_2NF (
    Course_ID VARCHAR(10) PRIMARY KEY,
    Course_Name VARCHAR(50) NOT NULL,
    Faculty_ID VARCHAR(10) NOT NULL,
    Faculty_Name VARCHAR(50) NOT NULL
);
INSERT INTO Courses_2NF VALUES
('C01', 'DBMS', 'F01', 'Sharma'),
('C02', 'Java', 'F02', 'Verma'),
('C03', 'Python', 'F03', 'Khan'),
('C04', 'SQL', 'F01', 'Sharma');
SELECT * FROM Courses_2NF;

-- Program 2: 3NF decomposition removes Faculty_Name transitive dependency.
CREATE TABLE Faculty (
    Faculty_ID VARCHAR(10) PRIMARY KEY,
    Faculty_Name VARCHAR(50) NOT NULL
);
CREATE TABLE Courses_3NF (
    Course_ID VARCHAR(10) PRIMARY KEY,
    Course_Name VARCHAR(50) NOT NULL,
    Faculty_ID VARCHAR(10) NOT NULL,
    FOREIGN KEY (Faculty_ID) REFERENCES Faculty(Faculty_ID)
);
INSERT INTO Faculty VALUES
('F01', 'Sharma'), ('F02', 'Verma'), ('F03', 'Khan');
INSERT INTO Courses_3NF VALUES
('C01', 'DBMS', 'F01'), ('C02', 'Java', 'F02'),
('C03', 'Python', 'F03'), ('C04', 'SQL', 'F01');

-- Program 3: display course and faculty details.
SELECT c.Course_ID, c.Course_Name, f.Faculty_ID, f.Faculty_Name
FROM Courses_3NF c
JOIN Faculty f ON f.Faculty_ID = c.Faculty_ID
ORDER BY c.Course_ID;

-- Program 4: update faculty once; all courses reflect the change.
UPDATE Faculty SET Faculty_Name = 'Sharma Sir' WHERE Faculty_ID = 'F01';
SELECT c.Course_Name, f.Faculty_Name
FROM Courses_3NF c JOIN Faculty f ON f.Faculty_ID = c.Faculty_ID;

-- Program 5: 3NF-but-not-BCNF relation from the lab scenario.
-- Candidate keys: (Student, Instructor) and (Student, Subject).
-- Instructor -> Subject is a determinant that is not a superkey.
CREATE TABLE Student_Instructor_Subject (
    Student VARCHAR(30),
    Instructor VARCHAR(10),
    Subject VARCHAR(30),
    PRIMARY KEY (Student, Instructor),
    UNIQUE (Student, Subject)
);
INSERT INTO Student_Instructor_Subject VALUES
('Asha', 'I01', 'DBMS'),
('Ravi', 'I01', 'DBMS'),
('Asha', 'I02', 'Java'),
('Mohan', 'I02', 'Java');
SELECT * FROM Student_Instructor_Subject;

-- Program 6: BCNF decomposition.
CREATE TABLE Instructor_Subject (
    Instructor VARCHAR(10) PRIMARY KEY,
    Subject VARCHAR(30) NOT NULL
);
CREATE TABLE Student_Instructor (
    Student VARCHAR(30),
    Instructor VARCHAR(10),
    PRIMARY KEY (Student, Instructor),
    FOREIGN KEY (Instructor) REFERENCES Instructor_Subject(Instructor)
);
INSERT INTO Instructor_Subject VALUES ('I01', 'DBMS'), ('I02', 'Java');
INSERT INTO Student_Instructor VALUES
('Asha', 'I01'), ('Ravi', 'I01'), ('Asha', 'I02'), ('Mohan', 'I02');

-- Program 7: reconstruct original information using JOIN.
SELECT si.Student, si.Instructor, ins.Subject
FROM Student_Instructor si
JOIN Instructor_Subject ins ON ins.Instructor = si.Instructor
ORDER BY si.Student, si.Instructor;
