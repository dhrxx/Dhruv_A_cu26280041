# Delete a student record using roll number.
import sqlite3
try:
    roll = int(input("Roll number to delete: "))
    with sqlite3.connect("crud_student_db.sqlite") as con:
        cur = con.execute("DELETE FROM students WHERE id=?", (roll,))
    print("Record deleted." if cur.rowcount else "Student not found.")
except (ValueError, sqlite3.Error) as error:
    print("Input/database error:", error)
