# Retrieve students whose marks are greater than 80.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        rows = con.execute("SELECT * FROM students WHERE marks > ?", (80,)).fetchall()
    for row in rows: print(row)
except sqlite3.Error as error:
    print("Database error:", error)
