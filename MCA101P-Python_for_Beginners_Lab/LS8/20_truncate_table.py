# Empty the table while preserving its structure (SQLite equivalent of TRUNCATE).
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        con.execute("DELETE FROM students")
    print("All rows deleted; table structure is unchanged.")
except sqlite3.Error as error:
    print("Database error:", error)
