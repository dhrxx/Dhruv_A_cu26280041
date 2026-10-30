# Create the students table.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        con.execute("""CREATE TABLE IF NOT EXISTS students(
            id INTEGER PRIMARY KEY, name TEXT NOT NULL,
            age INTEGER NOT NULL, marks REAL NOT NULL)""")
    print("students table created/verified.")
except sqlite3.Error as error:
    print("Database error:", error)
