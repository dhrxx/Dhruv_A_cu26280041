-- Run as database administrator. Replace the example password.
CREATE USER IF NOT EXISTS 'college_app'@'localhost'
  IDENTIFIED BY 'REPLACE_WITH_A_STRONG_PASSWORD';
GRANT SELECT, INSERT, UPDATE ON college_db.* TO 'college_app'@'localhost';
SHOW GRANTS FOR 'college_app'@'localhost';
