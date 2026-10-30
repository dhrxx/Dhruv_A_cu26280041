# Update a student's name using their ID.
import sqlite3
try:
    student_id = int(input("Student ID: "))
    name = input("New name: ").strip()
    with sqlite3.connect("student_db.sqlite") as con:
        cursor = con.execute("UPDATE students SET name=? WHERE id=?", (name, student_id))
    print("Name updated." if cursor.rowcount else "Student ID not found.")
except (ValueError, sqlite3.Error) as error:
    print("Input/database error:", error)
