# Group students by age and count students in each age group.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        rows = con.execute("SELECT age, COUNT(*) FROM students GROUP BY age ORDER BY age").fetchall()
    for age, count in rows: print(f"Age {age}: {count} student(s)")
except sqlite3.Error as error:
    print("Database error:", error)
