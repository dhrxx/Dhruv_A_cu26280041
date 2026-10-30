# Retrieve and display all student records.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        rows = con.execute("SELECT * FROM students").fetchall()
    for row in rows: print(row)
    if not rows: print("No student records.")
except sqlite3.Error as error:
    print("Database error:", error)
