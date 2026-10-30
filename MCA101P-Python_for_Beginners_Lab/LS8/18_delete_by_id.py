# Delete one student record by ID.
import sqlite3
try:
    student_id = int(input("Student ID to delete: "))
    with sqlite3.connect("student_db.sqlite") as con:
        cursor = con.execute("DELETE FROM students WHERE id=?", (student_id,))
    print("Record deleted." if cursor.rowcount else "Student ID not found.")
except (ValueError, sqlite3.Error) as error:
    print("Input/database error:", error)
