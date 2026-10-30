# Insert one student using a parameterized query.
import sqlite3
try:
    values = (int(input("ID: ")), input("Name: ").strip(),
              int(input("Age: ")), float(input("Marks: ")))
    with sqlite3.connect("student_db.sqlite") as con:
        con.execute("INSERT INTO students(id,name,age,marks) VALUES(?,?,?,?)", values)
    print("Student inserted.")
except (ValueError, sqlite3.Error) as error:
    print("Input/database error:", error)
