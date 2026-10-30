# Calculate the average marks of all students.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        average = con.execute("SELECT AVG(marks) FROM students").fetchone()[0]
    print("Average marks:", average if average is not None else "No records")
except sqlite3.Error as error:
    print("Database error:", error)
