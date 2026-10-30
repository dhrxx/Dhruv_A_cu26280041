# List all tables in the connected database.
import sqlite3
try:
    with sqlite3.connect("student_db.sqlite") as con:
        rows = con.execute("""SELECT name FROM sqlite_master
                              WHERE type='table' ORDER BY name""").fetchall()
    print("Tables:", [row[0] for row in rows])
except sqlite3.Error as error:
    print("Database error:", error)
