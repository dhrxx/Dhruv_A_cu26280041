# Import rows from students_import.csv into the students table.
import sqlite3, csv
try:
    with open("students_import.csv", "r", newline="", encoding="utf-8") as file:
        rows = [(int(r["id"]), r["name"], int(r["age"]), float(r["marks"]))
                for r in csv.DictReader(file)]
    with sqlite3.connect("student_db.sqlite") as con:
        con.executemany("INSERT OR REPLACE INTO students VALUES(?,?,?,?)", rows)
    print(f"Imported {len(rows)} record(s).")
except FileNotFoundError:
    print("Create students_import.csv with headers: id,name,age,marks")
except (ValueError, KeyError, sqlite3.Error, OSError) as error:
    print("Import error:", error)
