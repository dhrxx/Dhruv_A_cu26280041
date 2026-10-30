# Check whether the students table exists.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        row = con.execute("""SELECT name FROM sqlite_master
                             WHERE type='table' AND name=?""",
                          ("students",)).fetchone()
    print("students table exists." if row else "students table does not exist.")
except sqlite3.Error as error:
    print("Database error:", error)
