# Search for a student using roll number.
import sqlite3
try:
    roll = int(input("Roll number: "))
    with sqlite3.connect("crud_student_db.sqlite") as con:
        row = con.execute("SELECT * FROM students WHERE id=?", (roll,)).fetchone()
    print(row if row else "Student not found.")
except (ValueError, sqlite3.Error) as error:
    print("Input/database error:", error)
