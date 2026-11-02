-- DBMS Lab: Serializability, Isolation, Atomicity
-- WARNING: resets SerialIsolationLab.
CREATE DATABASE IF NOT EXISTS SerialIsolationLab;
USE SerialIsolationLab;
DROP TABLE IF EXISTS Accounts;
CREATE TABLE Accounts (
    Account_ID INT PRIMARY KEY,
    Account_Name VARCHAR(40) NOT NULL,
    Balance DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB;
INSERT INTO Accounts VALUES (1,'Asha',1000.00),(2,'Ravi',500.00);
SELECT * FROM Accounts;

-- Experiment 2: atomicity using ROLLBACK.
START TRANSACTION;
UPDATE Accounts SET Balance=Balance-200 WHERE Account_ID=1;
UPDATE Accounts SET Balance=Balance+200 WHERE Account_ID=2;
SELECT * FROM Accounts; -- see own uncommitted changes
ROLLBACK;
SELECT * FROM Accounts; -- original balances restored

-- Experiment 3: commit a complete transfer.
START TRANSACTION;
UPDATE Accounts SET Balance=Balance-200 WHERE Account_ID=1;
UPDATE Accounts SET Balance=Balance+200 WHERE Account_ID=2;
COMMIT;
SELECT * FROM Accounts; -- Asha 800, Ravi 700; total remains 1500

-- Experiment 4: set session isolation level.
SELECT @@transaction_isolation;
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;

-- Experiment 5: READ COMMITTED, two separate sessions.
-- SESSION A:
-- SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
-- START TRANSACTION;
-- UPDATE Accounts SET Balance=Balance+100 WHERE Account_ID=1;
-- -- Do not commit.
-- SESSION B:
-- SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
-- START TRANSACTION;
-- SELECT * FROM Accounts WHERE Account_ID=1;
-- -- Should see last committed value, not A's uncommitted update.
-- COMMIT;
-- SESSION A:
-- COMMIT;
-- SESSION B (new statement/transaction):
-- SELECT * FROM Accounts WHERE Account_ID=1;

-- Experiment 6: REPEATABLE READ, two sessions.
-- SESSION A:
-- SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;
-- START TRANSACTION;
-- SELECT Balance FROM Accounts WHERE Account_ID=1;
-- SESSION B:
-- SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
-- START TRANSACTION;
-- UPDATE Accounts SET Balance=Balance+50 WHERE Account_ID=1;
-- COMMIT;
-- SESSION A:
-- SELECT Balance FROM Accounts WHERE Account_ID=1;
-- -- Ordinary consistent read generally returns same snapshot value.
-- COMMIT;

-- Experiment 7: READ UNCOMMITTED dirty-read test, two sessions.
-- SESSION A:
-- START TRANSACTION;
-- UPDATE Accounts SET Balance=Balance+500 WHERE Account_ID=2;
-- -- Leave uncommitted.
-- SESSION B:
-- SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
-- START TRANSACTION;
-- SELECT Balance FROM Accounts WHERE Account_ID=2;
-- -- May see the uncommitted value; timing matters.
-- SESSION A:
-- ROLLBACK;
-- SESSION B:
-- COMMIT;

-- Experiment 8: SERIALIZABLE test, two sessions.
-- SESSION A:
-- SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;
-- START TRANSACTION;
-- SELECT * FROM Accounts WHERE Account_ID=1;
-- -- Keep transaction open.
-- SESSION B:
-- START TRANSACTION;
-- UPDATE Accounts SET Balance=Balance+10 WHERE Account_ID=1;
-- -- May wait until A completes, depending on execution and engine behavior.
-- SESSION A:
-- COMMIT;
-- SESSION B:
-- COMMIT;

-- Experiment 9: conflict-serializable schedule (manual precedence graph).
-- T1: R(A), W(A); then T2: R(A), W(A).
-- Conflicts on A give edge T1 -> T2. No cycle: serializable as T1 then T2.

-- Experiment 10: non-conflict-serializable schedule (manual graph).
-- 1 T1: W(A); 2 T2: W(A); 3 T2: W(B); 4 T1: W(B).
-- A gives T1 -> T2; B gives T2 -> T1. Cycle: not conflict-serializable.
