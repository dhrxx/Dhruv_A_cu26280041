-- DBMS Lab: Lock protocols, deadlocks, 2PL and timestamp ordering
-- WARNING: resets LockingProtocolLab.
CREATE DATABASE IF NOT EXISTS LockingProtocolLab;
USE LockingProtocolLab;
DROP TABLE IF EXISTS Accounts;
CREATE TABLE Accounts (
    Account_ID INT PRIMARY KEY,
    Account_Name VARCHAR(40) NOT NULL,
    Balance DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB;
INSERT INTO Accounts VALUES (1,'Asha',1000.00),(2,'Ravi',500.00);
SELECT * FROM Accounts;

-- Experiment 2: row-lock observation. Run Session A and B separately.
-- SESSION A:
-- START TRANSACTION;
-- SELECT * FROM Accounts WHERE Account_ID=1 FOR UPDATE;
-- Keep the transaction open.
--
-- SESSION B:
-- START TRANSACTION;
-- UPDATE Accounts SET Balance=Balance+100 WHERE Account_ID=1;
-- This waits until Session A commits/rolls back.
--
-- SESSION A:
-- COMMIT;
-- SESSION B can now complete; finish with COMMIT.

-- Experiment 3: deadlock demonstration. Reset first if repeating.
-- SESSION A:
-- START TRANSACTION;
-- UPDATE Accounts SET Balance=Balance-10 WHERE Account_ID=1;
-- SESSION B:
-- START TRANSACTION;
-- UPDATE Accounts SET Balance=Balance-20 WHERE Account_ID=2;
-- SESSION A:
-- UPDATE Accounts SET Balance=Balance+10 WHERE Account_ID=2;
-- SESSION B:
-- UPDATE Accounts SET Balance=Balance+20 WHERE Account_ID=1;
-- InnoDB detects the circular wait and normally rolls back one victim.
-- Check the victim error, then COMMIT/ROLLBACK the surviving transaction.

-- Experiment 4: prevention by consistent lock order.
-- Run this same lock order in BOTH sessions:
-- START TRANSACTION;
-- SELECT * FROM Accounts WHERE Account_ID=1 FOR UPDATE;
-- SELECT * FROM Accounts WHERE Account_ID=2 FOR UPDATE;
-- -- Apply the required updates here.
-- COMMIT;

-- Experiment 5: demonstrate isolation and row locking.
-- SESSION A:
-- SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
-- START TRANSACTION;
-- UPDATE Accounts SET Balance=Balance+100 WHERE Account_ID=1;
-- -- Leave uncommitted.
-- SESSION B:
-- SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;
-- START TRANSACTION;
-- SELECT * FROM Accounts WHERE Account_ID=1;
-- -- Ordinary consistent read sees committed data, not Session A's pending update.
-- COMMIT;
-- SESSION A:
-- COMMIT;

-- Experiment 6: textbook 2PL schedule simulation (manual analysis).
-- T1 growing phase: acquire S(A), then X(B); perform reads/writes.
-- T1 shrinking phase: release locks; acquire NO new locks after first release.
-- T2 follows its own growing then shrinking phase.
-- A transaction that acquires a new lock after releasing one violates basic 2PL.

-- Experiment 7: schedule that violates 2PL (manual):
-- T1: LOCK-X(A); WRITE(A); UNLOCK(A); LOCK-X(B); WRITE(B).
-- This is not permitted by basic 2PL because T1 acquires X(B) after entering
-- its shrinking phase by releasing A.

-- Experiment 8: textbook timestamp-ordering simulation (manual exercise).
-- Assign TS(T1)=10 and TS(T2)=20. For each item X track:
-- read_TS(X) = largest timestamp of a successful reader;
-- write_TS(X) = largest timestamp of a successful writer.
-- Read rule: reject R_i(X) if TS(T_i) < write_TS(X); otherwise allow and
-- update read_TS(X) = max(read_TS(X), TS(T_i)).
-- Write rule: reject W_i(X) if TS(T_i) < read_TS(X) or TS(T_i) < write_TS(X);
-- otherwise allow and update write_TS(X) = TS(T_i).
-- This is a paper/simulation exercise; it does not change InnoDB's algorithm.

-- Experiment 9: inspect latest InnoDB engine status after a deadlock.
SHOW ENGINE INNODB STATUS;
