# Connect to the SQLite database and display a success message.
import sqlite3
try:
    con = sqlite3.connect("student_db.sqlite")
    print("Connected to student_db.sqlite successfully.")
    con.close()
except sqlite3.Error as error:
    print("Database error:", error)
