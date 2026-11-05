-- Create the second table used by the transaction demonstration.
USE college_db;
CREATE TABLE IF NOT EXISTS student_profiles (
    student_id INT UNSIGNED PRIMARY KEY,
    phone VARCHAR(20),
    CONSTRAINT fk_profile_student FOREIGN KEY (student_id)
      REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;
