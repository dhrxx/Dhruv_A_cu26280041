# Find the student with the highest marks.
import sqlite3
try:
    with sqlite3.connect("crud_student_db.sqlite") as con:
        row = con.execute("SELECT * FROM students ORDER BY marks DESC LIMIT 1").fetchone()
    print("Top student:", row if row else "No records found.")
except sqlite3.Error as error:
    print("Database error:", error)
