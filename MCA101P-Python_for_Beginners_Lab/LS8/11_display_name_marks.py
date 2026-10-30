# Display only name and marks columns.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        rows = con.execute("SELECT name, marks FROM students").fetchall()
    for row in rows: print("Name:", row[0], "| Marks:", row[1])
except sqlite3.Error as error:
    print("Database error:", error)
