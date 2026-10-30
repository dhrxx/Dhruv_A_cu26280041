# Menu-driven database application: Add, View, Update, Delete.
import sqlite3
DB = "crud_student_db.sqlite"

def setup():
    with sqlite3.connect(DB) as con:
        con.execute("""CREATE TABLE IF NOT EXISTS students(
            id INTEGER PRIMARY KEY, name TEXT NOT NULL,
            age INTEGER NOT NULL, marks REAL NOT NULL)""")

def add():
    row = (int(input("ID: ")), input("Name: ").strip(),
           int(input("Age: ")), float(input("Marks: ")))
    with sqlite3.connect(DB) as con:
        con.execute("INSERT INTO students VALUES(?,?,?,?)", row)
    print("Student added.")

def view():
    with sqlite3.connect(DB) as con:
        rows = con.execute("SELECT * FROM students ORDER BY id").fetchall()
    for row in rows: print(row)
    if not rows: print("No student records.")

def update():
    sid, marks = int(input("Student ID: ")), float(input("New marks: "))
    with sqlite3.connect(DB) as con:
        cur = con.execute("UPDATE students SET marks=? WHERE id=?", (marks, sid))
    print("Updated." if cur.rowcount else "Student not found.")

def delete():
    sid = int(input("Student ID: "))
    with sqlite3.connect(DB) as con:
        cur = con.execute("DELETE FROM students WHERE id=?", (sid,))
    print("Deleted." if cur.rowcount else "Student not found.")

def main():
    setup()
    actions = {"1": add, "2": view, "3": update, "4": delete}
    while True:
        print("\\n1.Add  2.View  3.Update marks  4.Delete  5.Exit")
        choice = input("Choose: ")
        if choice == "5": break
        try:
            if choice in actions: actions[choice]()
            else: print("Invalid choice.")
        except (ValueError, sqlite3.Error) as error:
            print("Input/database error:", error)

if __name__ == "__main__":
    main()
