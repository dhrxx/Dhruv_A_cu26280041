# LEFT JOIN displays every student, even without a matching course.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        rows = con.execute("""SELECT students.name, courses.course_name
            FROM students LEFT JOIN courses ON students.id=courses.student_id""").fetchall()
    for row in rows: print(row)
except sqlite3.Error as error:
    print("Database error:", error)
