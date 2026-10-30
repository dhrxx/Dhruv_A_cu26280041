# Count total records in the students table.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        total = con.execute("SELECT COUNT(*) FROM students").fetchone()[0]
    print("Total records:", total)
except sqlite3.Error as error:
    print("Database error:", error)
