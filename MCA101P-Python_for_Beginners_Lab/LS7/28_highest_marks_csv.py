# Find the student with the highest marks in a CSV file.
import csv
try:
    with open("students.csv", "r", newline="", encoding="utf-8") as file:
        students = list(csv.DictReader(file))
    if students:
        topper = max(students, key=lambda row: float(row["Marks"]))
        print("Highest marks:", topper["Name"], "-", topper["Marks"])
    else:
        print("No student records found.")
except (OSError, KeyError, ValueError) as error:
    print("CSV/data error:", error)
