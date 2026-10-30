# Update student marks using roll number.
import sqlite3
try:
    roll, marks = int(input("Roll number: ")), float(input("New marks: "))
    with sqlite3.connect("crud_student_db.sqlite") as con:
        cur = con.execute("UPDATE students SET marks=? WHERE id=?", (marks, roll))
    print("Marks updated." if cur.rowcount else "Student not found.")
except (ValueError, sqlite3.Error) as error:
    print("Input/database error:", error)
