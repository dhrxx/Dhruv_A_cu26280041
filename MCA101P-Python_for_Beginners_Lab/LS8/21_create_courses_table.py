# Create a courses table and insert sample course data.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        con.execute("""CREATE TABLE IF NOT EXISTS courses(
            course_id INTEGER PRIMARY KEY, student_id INTEGER,
            course_name TEXT NOT NULL,
            FOREIGN KEY(student_id) REFERENCES students(id))""")
        con.executemany("INSERT OR REPLACE INTO courses VALUES(?,?,?)", [
            (101, 1, "Python"), (102, 2, "Database"), (103, 3, "Web Design")])
    print("Courses table created and sample data inserted.")
except sqlite3.Error as error:
    print("Database error:", error)
