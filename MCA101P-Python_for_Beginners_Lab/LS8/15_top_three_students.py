# Display the three students with the highest marks.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        rows = con.execute("SELECT * FROM students ORDER BY marks DESC LIMIT 3").fetchall()
    for row in rows: print(row)
except sqlite3.Error as error:
    print("Database error:", error)
