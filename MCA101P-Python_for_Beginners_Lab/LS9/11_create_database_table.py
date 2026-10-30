# Connect to SQLite and create the students table.
import sqlite3
try:
    with sqlite3.connect("crud_student_db.sqlite") as con:
        con.execute("""CREATE TABLE IF NOT EXISTS students(
            id INTEGER PRIMARY KEY, name TEXT NOT NULL,
            age INTEGER NOT NULL, marks REAL NOT NULL)""")
    print("Database connected and students table created.")
except sqlite3.Error as error:
    print("Database error:", error)
