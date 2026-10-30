# Display the structure of the students table.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        rows = con.execute("PRAGMA table_info(students)").fetchall()
    for row in rows:
        print(f"Column: {row[1]}, Type: {row[2]}, Primary key: {bool(row[5])}")
except sqlite3.Error as error:
    print("Database error:", error)
