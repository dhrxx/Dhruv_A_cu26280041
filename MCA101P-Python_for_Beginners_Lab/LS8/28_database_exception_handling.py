# Demonstrate handling and displaying SQLite database errors.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        # This intentionally references a missing table to demonstrate the exception.
        con.execute("SELECT * FROM table_that_does_not_exist").fetchall()
except sqlite3.Error as error:
    print("Database error handled:", error)
