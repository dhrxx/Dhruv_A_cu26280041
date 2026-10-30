# Search for a student by name in a CSV file.
import csv
name = input("Enter student name: ").strip().casefold()
try:
    with open("students.csv", "r", newline="", encoding="utf-8") as file:
        matches = [row for row in csv.DictReader(file)
                   if row.get("Name", "").casefold() == name]
    print(*matches, sep="\n") if matches else print("Student not found.")
except OSError as error:
    print("CSV error:", error)
