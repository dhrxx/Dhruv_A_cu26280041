# SQLite creates the database file automatically when it is opened.
import sqlite3
try:
    con = sqlite3.connect("student_db.sqlite")
    print("Database student_db.sqlite created/opened successfully.")
    con.close()
except sqlite3.Error as error:
    print("Database error:", error)
