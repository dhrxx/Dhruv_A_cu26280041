# Drop the students table. This permanently deletes its records.
import sqlite3
confirm = input("Type YES to drop the students table: ")
if confirm == "YES":
    try:
        with sqlite3.connect("student_db.sqlite") as con:
            con.execute("DROP TABLE IF EXISTS students")
        print("students table dropped.")
    except sqlite3.Error as error:
        print("Database error:", error)
else:
    print("Operation cancelled.")
