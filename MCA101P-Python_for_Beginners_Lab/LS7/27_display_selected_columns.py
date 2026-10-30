# Display selected columns (Name and Marks) from a CSV file.
import csv
try:
    with open("students.csv", "r", newline="", encoding="utf-8") as file:
        for row in csv.DictReader(file):
            print("Name:", row.get("Name"), "| Marks:", row.get("Marks"))
except OSError as error:
    print("CSV error:", error)
