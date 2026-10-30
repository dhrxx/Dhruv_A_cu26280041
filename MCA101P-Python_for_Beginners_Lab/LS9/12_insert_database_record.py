# Insert one record into the database students table.
import sqlite3
try:
    record = (int(input("Roll/ID: ")), input("Name: "),
              int(input("Age: ")), float(input("Marks: ")))
    with sqlite3.connect("crud_student_db.sqlite") as con:
        con.execute("INSERT INTO students VALUES(?,?,?,?)", record)
    print("Record inserted.")
except (ValueError, sqlite3.Error) as error:
    print("Input/database error:", error)
