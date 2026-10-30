# Insert multiple sample student records.
import sqlite3
records = [(1, "Asha", 20, 88), (2, "Rohan", 21, 76), (3, "Meera", 19, 93)]
try:
    with sqlite3.connect("student_db.sqlite") as con:
        con.executemany("INSERT OR REPLACE INTO students VALUES(?,?,?,?)", records)
    print("Multiple records inserted.")
except sqlite3.Error as error:
    print("Database error:", error)
