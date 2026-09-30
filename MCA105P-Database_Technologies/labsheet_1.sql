CREATE DATABASE dbms_lab;
use dbms_lab;
create table departments(
    dept_id int primary key,
    dept_name varchar(50)
);
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    first_name VARCHAR(30),
    salary DECIMAL(10,2)
);
CREATE TABLE projects (
    project_id INT PRIMARY KEY,
    project_name VARCHAR(100),
    start_date DATE
);
CREATE TABLE audit_logs (
    log_id INT PRIMARY KEY,
    action TEXT,
    created_at TIMESTAMP
);
CREATE TABLE products (
    product_id INT PRIMARY KEY,
    product_name VARCHAR(50),
    is_available BOOLEAN
);
CREATE TABLE system_settings (
    setting_id SMALLINT PRIMARY KEY,
    setting_key VARCHAR(50),
    setting_value VARCHAR(100)
);
ALTER TABLE employees
ADD email VARCHAR(100);
ALTER TABLE products
DROP COLUMN is_available;
ALTER TABLE departments
RENAME TO dept_units;
TRUNCATE TABLE audit_logs;
CREATE TABLE user_profiles (
    user_id INT PRIMARY KEY,
    bio TEXT,
    profile_pic BLOB
);
CREATE TABLE ticket_orders (
    ticket_id INT PRIMARY KEY,
    priority_level ENUM('Low', 'Medium', 'High')
);
DROP TABLE products;
ALTER TABLE employees
MODIFY email VARCHAR(150);
CREATE TABLE sensor_data (
    read_id BIGINT PRIMARY KEY,
    temperature FLOAT
);
CREATE TABLE student_courses (
    student_id INT,
    course_id INT
);
ALTER TABLE student_courses
ADD PRIMARY KEY (student_id, course_id);
ALTER TABLE employees
RENAME COLUMN first_name TO given_name;
ALTER TABLE system_settings
DROP PRIMARY KEY;
CREATE TABLE clients (
    client_id INT,
    registration_time TIME
);
ALTER TABLE clients
ADD PRIMARY KEY (client_id);
CREATE TABLE order_items (
    order_id INT,
    item_id INT,
    quantity INT,
    unit_price DECIMAL(8,2),
    PRIMARY KEY (order_id, item_id)
);
CREATE TABLE event_schedules (
    event_id INT,
    venue_id INT,
    event_date DATE,
    duration_hours TINYINT,
    PRIMARY KEY (event_id, venue_id)
);
ALTER TABLE employees
ADD hire_date DATE,
ADD is_active BOOLEAN;
ALTER TABLE employees
DROP COLUMN email,
MODIFY salary DECIMAL(12,2);
CREATE TABLE inventory (
    warehouse_id INT,
    sku VARCHAR(20)
);
ALTER TABLE inventory
ADD PRIMARY KEY (warehouse_id, sku);
ALTER TABLE user_profiles
MODIFY bio MEDIUMTEXT;
ALTER TABLE employees
ADD middle_name VARCHAR(30) AFTER given_name;
CREATE TABLE geolocations (
    location_id INT PRIMARY KEY,
    latitude DECIMAL(9,6),
    longitude DECIMAL(9,6)
);
CREATE TABLE user_accounts (
    user_id BIGINT PRIMARY KEY,
    account_type ENUM('Standard', 'Premium', 'Admin'),
    created_timestamp TIMESTAMP
);
CREATE TABLE device_logs (
    log_id BIGINT PRIMARY KEY,
    ip_address VARCHAR(45),
    payload LONGBLOB
);
CREATE TABLE order_items (
    order_id INT,
    item_id INT,
    quantity INT,
    unit_price DECIMAL(8,2),
    PRIMARY KEY (order_id, item_id)
);
ALTER TABLE order_items
DROP PRIMARY KEY,
ADD order_item_id INT FIRST,
ADD PRIMARY KEY (order_item_id);
ALTER TABLE projects
CHANGE start_date project_year YEAR;
CREATE TABLE document_store (
    doc_id INT PRIMARY KEY,
    doc_title VARCHAR(255),
    content LONGTEXT
);
ALTER TABLE document_store
CHANGE content doc_body LONGTEXT;
CREATE TABLE employees_archive LIKE employees;
DROP TABLE IF EXISTS employees_archive;
CREATE TABLE financial_ledger (
    entry_id BIGINT PRIMARY KEY,
    debit DECIMAL(15,4),
    credit DECIMAL(15,4)
);
ALTER TABLE financial_ledger
ADD transaction_date DATETIME FIRST;
CREATE TABLE measurements (
    sample_id INT PRIMARY KEY,
    value DOUBLE
);
CREATE TABLE app_users (
    user_id INT PRIMARY KEY,
    status_code TINYINT
);
CREATE TABLE transactions (
    xact_id BIGINT PRIMARY KEY,
    user_id INT,
    amount DECIMAL(10,2),
    channel ENUM('Web', 'Mobile', 'ATM'),
    status VARCHAR(20),
    xact_time TIMESTAMP
);
CREATE TABLE system_events (
    event_id BIGINT,
    node_id SMALLINT,
    event_payload TEXT,
    logged_time TIMESTAMP,
    PRIMARY KEY (event_id, node_id)
);
ALTER TABLE clients
DROP PRIMARY KEY,
ADD region_code CHAR(3),
ADD PRIMARY KEY (client_id, region_code);
CREATE TABLE raw_data (
    temp_val VARCHAR(50)
);
ALTER TABLE raw_data
CHANGE temp_val converted_val DECIMAL(6,3),
ADD processed_at TIMESTAMP AFTER converted_val;
CREATE TABLE student_enrollments (
    student_id INT,
    course_id INT,
    semester VARCHAR(10),
    enrollment_date DATE,
    PRIMARY KEY (student_id, course_id, semester)
);
ALTER TABLE student_enrollments
DROP PRIMARY KEY,
ADD enrollment_id BIGINT FIRST,
ADD PRIMARY KEY (enrollment_id);
CREATE TABLE file_metadata (
    file_id INT PRIMARY KEY,
    file_path VARCHAR(500),
    file_size_bytes BIGINT,
    checksum CHAR(64)
);
ALTER TABLE file_metadata
MODIFY file_id BIGINT,
MODIFY file_path TEXT,
DROP COLUMN checksum;
CREATE TABLE sensor_readings (
    sensor_id INT,
    recorded_at TIMESTAMP,
    reading FLOAT,
    PRIMARY KEY (sensor_id, recorded_at)
);
RENAME TABLE sensor_readings TO historical_sensor_readings;
CREATE TABLE application_logs (
    log_id BIGINT PRIMARY KEY,
    level ENUM('DEBUG', 'INFO', 'WARN', 'ERROR'),
    message TEXT,
    execution_time DOUBLE,
    created_at DATETIME
);
ALTER TABLE application_logs
MODIFY level VARCHAR(10);
