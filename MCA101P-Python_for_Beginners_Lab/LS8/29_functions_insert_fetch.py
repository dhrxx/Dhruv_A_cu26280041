# Use functions to insert and fetch student data (modular approach).
import sqlite3

def insert_student(student):
    with sqlite3.connect("student_db.sqlite") as con:
        con.execute("INSERT INTO students(id,name,age,marks) VALUES(?,?,?,?)", student)

def fetch_students():
    with sqlite3.connect("student_db.sqlite") as con:
        return con.execute("SELECT * FROM students ORDER BY id").fetchall()

try:
    insert_student((99, "Sample Student", 20, 85))
    print("Inserted sample record.")
    for student in fetch_students():
        print(student)
except sqlite3.Error as error:
    print("Database error:", error)
