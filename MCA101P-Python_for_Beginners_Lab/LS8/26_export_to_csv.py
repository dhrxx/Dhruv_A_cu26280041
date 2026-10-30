# Export the students table to a CSV file.
import sqlite3, csv
try:
    with sqlite3.connect("student_db.sqlite") as con:
        cursor = con.execute("SELECT id,name,age,marks FROM students")
        rows = cursor.fetchall()
    with open("students_export.csv", "w", newline="", encoding="utf-8") as file:
        writer = csv.writer(file)
        writer.writerow(["id", "name", "age", "marks"])
        writer.writerows(rows)
    print("Exported to students_export.csv.")
except (sqlite3.Error, OSError) as error:
    print("Export error:", error)
