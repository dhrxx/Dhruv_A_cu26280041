-- DBMS Lab: ACID Properties and Transaction Management
-- WARNING: resets TransactionLab.
CREATE DATABASE IF NOT EXISTS TransactionLab;
USE TransactionLab;
DROP TABLE IF EXISTS Accounts;
CREATE TABLE Accounts (
    Account_ID INT PRIMARY KEY,
    Account_Name VARCHAR(50) NOT NULL,
    Balance DECIMAL(10,2) NOT NULL,
    CONSTRAINT chk_balance_nonnegative CHECK (Balance >= 0)
) ENGINE=InnoDB;
INSERT INTO Accounts VALUES
(101,'Asha',1000.00), (102,'Ravi',500.00);
SELECT * FROM Accounts;

-- Experiment 2: partial update risk without a transaction.
-- Execute only the debit to simulate an application failure before credit.
UPDATE Accounts SET Balance=Balance-200 WHERE Account_ID=101;
SELECT * FROM Accounts;
-- Reset balances before the next experiment.
UPDATE Accounts SET Balance=1000 WHERE Account_ID=101;
UPDATE Accounts SET Balance=500 WHERE Account_ID=102;

-- Experiment 3: complete transfer with atomic transaction.
START TRANSACTION;
UPDATE Accounts SET Balance=Balance-200 WHERE Account_ID=101;
UPDATE Accounts SET Balance=Balance+200 WHERE Account_ID=102;
SELECT * FROM Accounts;
COMMIT;
SELECT * FROM Accounts;

-- Experiment 4: rollback an incomplete transaction.
START TRANSACTION;
UPDATE Accounts SET Balance=Balance-100 WHERE Account_ID=101;
SELECT * FROM Accounts;
ROLLBACK;
SELECT * FROM Accounts;

-- Experiment 5: consistency rule using CHECK.
-- This statement should fail because it would make the balance negative.
-- If the client reports an error, explicitly issue ROLLBACK before continuing.
START TRANSACTION;
UPDATE Accounts SET Balance=Balance-5000 WHERE Account_ID=101;
ROLLBACK;
SELECT * FROM Accounts;

-- Experiment 6: SAVEPOINT.
START TRANSACTION;
UPDATE Accounts SET Balance=Balance-50 WHERE Account_ID=101;
SAVEPOINT after_debit;
UPDATE Accounts SET Balance=Balance+50 WHERE Account_ID=102;
ROLLBACK TO SAVEPOINT after_debit;
ROLLBACK;
SELECT * FROM Accounts;

-- Experiment 7: READ COMMITTED isolation test (two sessions).
-- SESSION A:
-- USE TransactionLab;
-- SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
-- START TRANSACTION;
-- UPDATE Accounts SET Balance=Balance+100 WHERE Account_ID=101;
-- -- Leave uncommitted.
-- SESSION B:
-- USE TransactionLab;
-- SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
-- START TRANSACTION;
-- SELECT * FROM Accounts WHERE Account_ID=101;
-- COMMIT;
-- SESSION A:
-- COMMIT;
-- SESSION B:
-- SELECT * FROM Accounts WHERE Account_ID=101;

-- Experiment 8: compare supported isolation settings.
SELECT @@transaction_isolation;
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;
SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;
-- Optional dirty-read test setting:
SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;

-- Experiment 9: durability observation.
START TRANSACTION;
UPDATE Accounts SET Balance=Balance+25 WHERE Account_ID=102;
COMMIT;
-- Verify from a separate/new connection:
SELECT * FROM Accounts WHERE Account_ID=102;
