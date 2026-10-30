# Read and display all records from the database table.
import sqlite3
try:
    with sqlite3.connect("crud_student_db.sqlite") as con:
        rows = con.execute("SELECT * FROM students").fetchall()
    for row in rows: print(row)
    if not rows: print("No records.")
except sqlite3.Error as error:
    print("Database error:", error)
