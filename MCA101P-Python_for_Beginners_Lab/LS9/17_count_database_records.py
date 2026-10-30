# Count and display the total number of student records.
import sqlite3
try:
    with sqlite3.connect("crud_student_db.sqlite") as con:
        count = con.execute("SELECT COUNT(*) FROM students").fetchone()[0]
    print("Total records:", count)
except sqlite3.Error as error:
    print("Database error:", error)
