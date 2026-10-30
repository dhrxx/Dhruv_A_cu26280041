# Count data rows and columns in a CSV file (header excluded from row count).
import csv
try:
    with open("students.csv", "r", newline="", encoding="utf-8") as file:
        rows = list(csv.reader(file))
    columns = len(rows[0]) if rows else 0
    print("Rows (excluding header):", max(0, len(rows) - 1))
    print("Columns:", columns)
except OSError as error:
    print("CSV error:", error)
