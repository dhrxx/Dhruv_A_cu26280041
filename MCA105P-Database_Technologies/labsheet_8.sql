-- MCA105P Database Technologies
-- Combined solution file: all questions for this lab sheet in one .sql file.
-- SQL dialect: MySQL / MariaDB
-- Note: Run in order. Some questions intentionally test constraint errors or alter/drop objects.

-- SETUP / SAMPLE DATA
-- Run this section first.

CREATE DATABASE IF NOT EXISTS Hospital_Functions_Lab; USE Hospital_Functions_Lab;
CREATE TABLE IF NOT EXISTS Patients(PatientID INT PRIMARY KEY,PatientName VARCHAR(50),Age INT,Gender VARCHAR(10),WeightKg DECIMAL(5,2),ConsultationFee DECIMAL(10,2));
CREATE TABLE IF NOT EXISTS Medicines(MedicineID INT PRIMARY KEY,MedicineName VARCHAR(50),UnitPrice DECIMAL(10,2),Stock INT);
INSERT IGNORE INTO Patients VALUES(201,'Asha',24,'F',55.5,600),(202,'Rohan',35,'M',72,800),(203,'Meera',29,'F',61.5,700),(204,'Vivek',46,'M',85,1000),(205,'Nitin',17,'M',58,500),(206,'Kavya',52,'F',68,900);
INSERT IGNORE INTO Medicines VALUES(301,'Paracetamol',25,100),(302,'Amoxicillin',80,50),(303,'Cetirizine',35,75),(304,'Omeprazole',60,40),(305,'Azithromycin',120,25);


----------------------------------------------------------------------------
-- Q01_calculate_bmi.sql
----------------------------------------------------------------------------

-- Calculate BMI
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS calculate_bmi;
DELIMITER //
CREATE FUNCTION calculate_bmi(weight_kg DECIMAL(5,2),height_m DECIMAL(4,2)) RETURNS DECIMAL(6,2) DETERMINISTIC RETURN ROUND(weight_kg/(height_m*height_m),2) //
DELIMITER ;
SELECT calculate_bmi(55.5,1.65) AS BMI;

-- Sample output / observation:
-- 20.39 for 55.5 kg and 1.65 m.


----------------------------------------------------------------------------
-- Q02_age_category.sql
----------------------------------------------------------------------------

-- Age Category
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS age_category;
DELIMITER //
CREATE FUNCTION age_category(p_age INT) RETURNS VARCHAR(10) DETERMINISTIC RETURN CASE WHEN p_age<18 THEN 'Child' WHEN p_age<60 THEN 'Adult' ELSE 'Senior' END //
DELIMITER ;
SELECT PatientName,Age,age_category(Age) AS Category FROM Patients;

-- Sample output / observation:
-- Nitin Child; remaining patients Adult.


----------------------------------------------------------------------------
-- Q03_consultation_fee_with_tax.sql
----------------------------------------------------------------------------

-- Consultation Fee With Tax
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS consultation_with_tax;
DELIMITER //
CREATE FUNCTION consultation_with_tax(fee DECIMAL(10,2),tax_percent DECIMAL(5,2)) RETURNS DECIMAL(10,2) DETERMINISTIC RETURN ROUND(fee*(1+tax_percent/100),2) //
DELIMITER ;
SELECT 600 AS OriginalFee,consultation_with_tax(600,18) AS FeeWithTax;

-- Sample output / observation:
-- 600.00 and 708.00.


----------------------------------------------------------------------------
-- Q04_medicine_stock_value.sql
----------------------------------------------------------------------------

-- Medicine Stock Value
-- Run in the database specified in this lab sheet.
DROP FUNCTION IF EXISTS medicine_stock_value;
DELIMITER //
CREATE FUNCTION medicine_stock_value(unit_price DECIMAL(10,2),p_stock INT) RETURNS DECIMAL(12,2) DETERMINISTIC RETURN unit_price*p_stock //
DELIMITER ;
SELECT MedicineName,medicine_stock_value(UnitPrice,Stock) AS StockValue FROM Medicines;

-- Sample output / observation:
-- Paracetamol 2500; Amoxicillin 4000; Cetirizine 2625; Omeprazole 2400; Azithromycin 3000.


----------------------------------------------------------------------------
-- Q05_patients_by_gender.sql
----------------------------------------------------------------------------

-- Patients By Gender
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS patient_by_gender;
DELIMITER //
CREATE PROCEDURE patient_by_gender(IN p_gender VARCHAR(10)) BEGIN SELECT * FROM Patients WHERE Gender=p_gender; END //
DELIMITER ;
CALL patient_by_gender('F'); CALL patient_by_gender('M');

-- Sample output / observation:
-- F: Asha, Meera, Kavya; M: Rohan, Vivek, Nitin.


----------------------------------------------------------------------------
-- Q06_patients_above_age.sql
----------------------------------------------------------------------------

-- Patients Above Age
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS patients_above_age;
DELIMITER //
CREATE PROCEDURE patients_above_age(IN p_age INT) BEGIN SELECT * FROM Patients WHERE Age>p_age; END //
DELIMITER ;
CALL patients_above_age(30); CALL patients_above_age(40);

-- Sample output / observation:
-- >30: Rohan, Vivek, Kavya; >40: Vivek, Kavya.


----------------------------------------------------------------------------
-- Q07_update_medicine_stock.sql
----------------------------------------------------------------------------

-- Update Medicine Stock
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS update_medicine_stock;
DELIMITER //
CREATE PROCEDURE update_medicine_stock(IN p_id INT,IN p_quantity INT) BEGIN UPDATE Medicines SET Stock=Stock+p_quantity WHERE MedicineID=p_id; SELECT * FROM Medicines WHERE MedicineID=p_id; END //
DELIMITER ;
CALL update_medicine_stock(301,10);

-- Sample output / observation:
-- Paracetamol stock becomes 110.


----------------------------------------------------------------------------
-- Q08_medicine_count_out.sql
----------------------------------------------------------------------------

-- Medicine Count OUT
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS medicine_count;
DELIMITER //
CREATE PROCEDURE medicine_count(OUT p_count INT) BEGIN SELECT COUNT(*) INTO p_count FROM Medicines; END //
DELIMITER ;
CALL medicine_count(@total); SELECT @total AS TotalMedicines;

-- Sample output / observation:
-- 5.


----------------------------------------------------------------------------
-- Q09_patient_report.sql
----------------------------------------------------------------------------

-- Patient Report
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS patient_report;
DELIMITER //
CREATE PROCEDURE patient_report() BEGIN SELECT PatientID,PatientName,Age,ROUND(WeightKg/POWER(1.65,2),2) AS BMI,CASE WHEN Age<18 THEN 'Child' WHEN Age<60 THEN 'Adult' ELSE 'Senior' END AS AgeCategory FROM Patients; END //
DELIMITER ;
CALL patient_report();

-- Sample output / observation:
-- Six rows; BMI assumes height 1.65 m because the supplied table has no height column.


----------------------------------------------------------------------------
-- Q10_medicine_report.sql
----------------------------------------------------------------------------

-- Medicine Report
-- Run in the database specified in this lab sheet.
DROP PROCEDURE IF EXISTS medicine_report;
DELIMITER //
CREATE PROCEDURE medicine_report() BEGIN SELECT MedicineID,MedicineName,UnitPrice,Stock,UnitPrice*Stock AS StockValue,CASE WHEN Stock<30 THEN 'Low Stock' WHEN Stock<60 THEN 'Medium Stock' ELSE 'In Stock' END AS StockStatus FROM Medicines; END //
DELIMITER ;
CALL medicine_report();

-- Sample output / observation:
-- Five rows; Azithromycin is Low Stock; Omeprazole and Amoxicillin Medium Stock.
