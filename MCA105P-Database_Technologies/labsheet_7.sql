-- MCA105P Database Technologies
-- Combined solution file: all questions for this lab sheet in one .sql file.
-- SQL dialect: MySQL / MariaDB
-- Note: Run in order. Some questions intentionally test constraint errors or alter/drop objects.

-- SETUP / SAMPLE DATA
-- Run this section first.

CREATE DATABASE IF NOT EXISTS Hospital_Cursor_Trigger_Lab; USE Hospital_Cursor_Trigger_Lab;
CREATE TABLE IF NOT EXISTS Doctors(DoctorID INT PRIMARY KEY,DoctorName VARCHAR(50),Department VARCHAR(40),ConsultationFee DECIMAL(10,2),Status VARCHAR(20));
CREATE TABLE IF NOT EXISTS Doctor_Audit(AuditID INT AUTO_INCREMENT PRIMARY KEY,DoctorID INT,ActionType VARCHAR(30),OldFee DECIMAL(10,2),NewFee DECIMAL(10,2),ActionTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS Fee_Log(LogID INT AUTO_INCREMENT PRIMARY KEY,DoctorID INT,DoctorName VARCHAR(50),OldFee DECIMAL(10,2),NewFee DECIMAL(10,2),ChangedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
INSERT IGNORE INTO Doctors VALUES(201,'Dr. Anil','Cardiology',1200,'Active'),(202,'Dr. Beena','Pediatrics',900,'Active'),(203,'Dr. Chirag','Neurology',1500,'Active'),(204,'Dr. Divya','Dermatology',1000,'Active'),(205,'Dr. Eshan','Cardiology',1300,'Active'),(206,'Dr. Farah','Pediatrics',850,'Active');
-- Run each trigger question in a fresh test schema or drop/rename triggers when names conflict.


----------------------------------------------------------------------------
-- Q01_display_doctor_ids_and_names.sql
----------------------------------------------------------------------------

-- Display Doctor IDs and Names
-- Run in the database specified in this lab sheet.
SELECT DoctorID,DoctorName FROM Doctors ORDER BY DoctorID;

-- Sample output / observation:
-- 201–206; six rows.


----------------------------------------------------------------------------
-- Q02_cursor_doctors_fee_greater_than_1000.sql
----------------------------------------------------------------------------

-- Cursor Doctors Fee Greater Than 1000
-- Run in the database specified in this lab sheet.
SELECT DoctorID,DoctorName,ConsultationFee FROM Doctors WHERE ConsultationFee>1000;

-- Sample output / observation:
-- Dr. Anil 1200, Dr. Chirag 1500, Dr. Eshan 1300.


----------------------------------------------------------------------------
-- Q03_cursor_total_consultation_fee.sql
----------------------------------------------------------------------------

-- Cursor Total Consultation Fee
-- Run in the database specified in this lab sheet.
SELECT SUM(ConsultationFee) AS TotalFee FROM Doctors;

-- Sample output / observation:
-- 6750.00.


----------------------------------------------------------------------------
-- Q04_cursor_cardiology_count.sql
----------------------------------------------------------------------------

-- Cursor Cardiology Count
-- Run in the database specified in this lab sheet.
SELECT COUNT(*) AS CardiologyDoctors FROM Doctors WHERE Department='Cardiology';

-- Sample output / observation:
-- 2.


----------------------------------------------------------------------------
-- Q05_cursor_average_consultation_fee.sql
----------------------------------------------------------------------------

-- Cursor Average Consultation Fee
-- Run in the database specified in this lab sheet.
SELECT COALESCE(AVG(ConsultationFee),0) AS AverageFee FROM Doctors;

-- Sample output / observation:
-- 1125.00.


----------------------------------------------------------------------------
-- Q06_increase_pediatrics_fees.sql
----------------------------------------------------------------------------

-- Increase Pediatrics Fees
-- Run in the database specified in this lab sheet.
UPDATE Doctors SET ConsultationFee=ROUND(ConsultationFee*1.10,2) WHERE Department='Pediatrics';
SELECT DoctorName,ConsultationFee FROM Doctors WHERE Department='Pediatrics';

-- Sample output / observation:
-- Dr. Beena 990.00; Dr. Farah 935.00.


----------------------------------------------------------------------------
-- Q07_mark_low_fee_doctors_for_review.sql
----------------------------------------------------------------------------

-- Mark Low Fee Doctors for Review
-- Run in the database specified in this lab sheet.
UPDATE Doctors SET Status='Review' WHERE ConsultationFee<1000;
SELECT DoctorName,Status FROM Doctors WHERE Status='Review';

-- Sample output / observation:
-- Dr. Beena and Dr. Farah.


----------------------------------------------------------------------------
-- Q08_ordered_doctor_report.sql
----------------------------------------------------------------------------

-- Ordered Doctor Report
-- Run in the database specified in this lab sheet.
SELECT DoctorName,Department,ConsultationFee FROM Doctors ORDER BY Department,DoctorName;

-- Sample output / observation:
-- Six doctors ordered by department and name.


----------------------------------------------------------------------------
-- Q09_count_active_doctors.sql
----------------------------------------------------------------------------

-- Count Active Doctors
-- Run in the database specified in this lab sheet.
SELECT COUNT(*) AS ActiveDoctors FROM Doctors WHERE Status='Active';

-- Sample output / observation:
-- 6.


----------------------------------------------------------------------------
-- Q10_increase_fees_at_least_1200.sql
----------------------------------------------------------------------------

-- Increase Fees At Least 1200
-- Run in the database specified in this lab sheet.
UPDATE Doctors SET ConsultationFee=ROUND(ConsultationFee*1.05,2) WHERE ConsultationFee>=1200;
SELECT DoctorName,ConsultationFee FROM Doctors;

-- Sample output / observation:
-- Anil 1260; Chirag 1575; Eshan 1365; others unchanged.


----------------------------------------------------------------------------
-- Q11_audit_doctor_insert.sql
----------------------------------------------------------------------------

-- Audit Doctor Insert
-- Run in the database specified in this lab sheet.
DELIMITER //
CREATE TRIGGER trg_doctor_insert AFTER INSERT ON Doctors FOR EACH ROW INSERT INTO Doctor_Audit(DoctorID,ActionType,NewFee) VALUES(NEW.DoctorID,'INSERT',NEW.ConsultationFee);
-- Test: INSERT INTO Doctors VALUES(299,'Dr. Test','General',700,'Active');
SELECT * FROM Doctor_Audit;
//
DELIMITER ;

-- Sample output / observation:
-- One INSERT audit row.


----------------------------------------------------------------------------
-- Q12_audit_fee_update.sql
----------------------------------------------------------------------------

-- Audit Fee Update
-- Run in the database specified in this lab sheet.
DELIMITER //
CREATE TRIGGER trg_fee_update AFTER UPDATE ON Doctors FOR EACH ROW INSERT INTO Doctor_Audit(DoctorID,ActionType,OldFee,NewFee) SELECT NEW.DoctorID,'UPDATE_FEE',OLD.ConsultationFee,NEW.ConsultationFee WHERE OLD.ConsultationFee<>NEW.ConsultationFee;
-- Test: UPDATE Doctors SET ConsultationFee=1250 WHERE DoctorID=201;
//
DELIMITER ;

-- Sample output / observation:
-- OldFee and NewFee logged.


----------------------------------------------------------------------------
-- Q13_uppercase_doctor_name.sql
----------------------------------------------------------------------------

-- Uppercase Doctor Name
-- Run in the database specified in this lab sheet.
DELIMITER //
CREATE TRIGGER trg_upper_name BEFORE INSERT ON Doctors FOR EACH ROW SET NEW.DoctorName=UPPER(NEW.DoctorName);
-- Test with lowercase name; SELECT DoctorName FROM Doctors WHERE DoctorID=299;
//
DELIMITER ;

-- Sample output / observation:
-- Inserted name is uppercase.


----------------------------------------------------------------------------
-- Q14_reject_negative_insert_fee.sql
----------------------------------------------------------------------------

-- Reject Negative Insert Fee
-- Run in the database specified in this lab sheet.
DELIMITER //
CREATE TRIGGER trg_nonnegative_insert BEFORE INSERT ON Doctors FOR EACH ROW BEGIN IF NEW.ConsultationFee<0 THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Consultation fee cannot be negative'; END IF; END;
-- Test: INSERT with ConsultationFee=-1; expected custom error.
//
DELIMITER ;

-- Sample output / observation:
-- Insert rejected with a meaningful error.


----------------------------------------------------------------------------
-- Q15_audit_doctor_delete.sql
----------------------------------------------------------------------------

-- Audit Doctor Delete
-- Run in the database specified in this lab sheet.
DELIMITER //
CREATE TRIGGER trg_doctor_delete AFTER DELETE ON Doctors FOR EACH ROW INSERT INTO Doctor_Audit(DoctorID,ActionType,OldFee) VALUES(OLD.DoctorID,'DELETE',OLD.ConsultationFee);
-- Test only on a disposable doctor row.
//
DELIMITER ;

-- Sample output / observation:
-- Deleted DoctorID and old fee logged.


----------------------------------------------------------------------------
-- Q16_fee_log_update.sql
----------------------------------------------------------------------------

-- Fee Log Update
-- Run in the database specified in this lab sheet.
DELIMITER //
CREATE TRIGGER trg_fee_log AFTER UPDATE ON Doctors FOR EACH ROW INSERT INTO Fee_Log(DoctorID,DoctorName,OldFee,NewFee) SELECT NEW.DoctorID,NEW.DoctorName,OLD.ConsultationFee,NEW.ConsultationFee WHERE OLD.ConsultationFee<>NEW.ConsultationFee;
//
DELIMITER ;

-- Sample output / observation:
-- Fee_Log contains old and new fees.


----------------------------------------------------------------------------
-- Q17_reject_negative_update_fee.sql
----------------------------------------------------------------------------

-- Reject Negative Update Fee
-- Run in the database specified in this lab sheet.
DELIMITER //
CREATE TRIGGER trg_nonnegative_update BEFORE UPDATE ON Doctors FOR EACH ROW BEGIN IF NEW.ConsultationFee<0 THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Consultation fee cannot be negative'; END IF; END;
//
DELIMITER ;

-- Sample output / observation:
-- Negative fee update rejected.


----------------------------------------------------------------------------
-- Q18_normalize_active_status.sql
----------------------------------------------------------------------------

-- Normalize Active Status
-- Run in the database specified in this lab sheet.
DELIMITER //
CREATE TRIGGER trg_status_case BEFORE UPDATE ON Doctors FOR EACH ROW SET NEW.Status=IF(LOWER(NEW.Status)='active','Active',NEW.Status);
-- Test: UPDATE Doctors SET Status='active' WHERE DoctorID=201;
//
DELIMITER ;

-- Sample output / observation:
-- Status saved as Active.


----------------------------------------------------------------------------
-- Q19_audit_department_change.sql
----------------------------------------------------------------------------

-- Audit Department Change
-- Run in the database specified in this lab sheet.
DELIMITER //
CREATE TRIGGER trg_department_change AFTER UPDATE ON Doctors FOR EACH ROW INSERT INTO Doctor_Audit(DoctorID,ActionType) SELECT NEW.DoctorID,'DEPARTMENT_CHANGE' WHERE OLD.Department<>NEW.Department;
//
DELIMITER ;

-- Sample output / observation:
-- Department change recorded in Doctor_Audit.


----------------------------------------------------------------------------
-- Q20_prevent_empty_doctor_name.sql
----------------------------------------------------------------------------

-- Prevent Empty Doctor Name
-- Run in the database specified in this lab sheet.
DELIMITER //
CREATE TRIGGER trg_name_required BEFORE INSERT ON Doctors FOR EACH ROW BEGIN IF TRIM(NEW.DoctorName)='' THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Doctor name is required'; END IF; END;
//
DELIMITER ;

-- Sample output / observation:
-- Empty name insert rejected.
