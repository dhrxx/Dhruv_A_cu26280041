# Update a student's marks using their ID.
import sqlite3
try:
    student_id = int(input("Student ID: "))
    marks = float(input("New marks: "))
    with sqlite3.connect("student_db.sqlite") as con:
        cursor = con.execute("UPDATE students SET marks=? WHERE id=?", (marks, student_id))
    print("Marks updated." if cursor.rowcount else "Student ID not found.")
except (ValueError, sqlite3.Error) as error:
    print("Input/database error:", error)
