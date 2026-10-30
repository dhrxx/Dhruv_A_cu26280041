# Display all student records sorted by student name.
import sqlite3
try:
    with sqlite3.connect("crud_student_db.sqlite") as con:
        rows = con.execute("SELECT * FROM students ORDER BY name ASC").fetchall()
    for row in rows: print(row)
except sqlite3.Error as error:
    print("Database error:", error)
