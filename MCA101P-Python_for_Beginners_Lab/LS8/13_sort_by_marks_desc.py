# Display records sorted by marks in descending order.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        rows = con.execute("SELECT * FROM students ORDER BY marks DESC").fetchall()
    for row in rows: print(row)
except sqlite3.Error as error:
    print("Database error:", error)
