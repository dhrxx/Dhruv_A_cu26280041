# Read and display all rows from a CSV file.
import csv
try:
    with open("students.csv", "r", newline="", encoding="utf-8") as file:
        for row in csv.reader(file):
            print(row)
except OSError as error:
    print("CSV error:", error)
