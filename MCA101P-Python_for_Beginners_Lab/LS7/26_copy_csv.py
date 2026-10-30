# Copy all CSV rows from one file to another.
import csv
try:
    with open("students.csv", "r", newline="", encoding="utf-8") as source:
        rows = list(csv.reader(source))
    with open("students_copy.csv", "w", newline="", encoding="utf-8") as target:
        csv.writer(target).writerows(rows)
    print("CSV data copied to students_copy.csv.")
except OSError as error:
    print("CSV error:", error)
