# Create a CSV file and write student records.
import csv
students = [
    ["ID", "Name", "Marks"],
    [1, "Asha", 88],
    [2, "Rohan", 76],
    [3, "Meera", 93],
]
try:
    with open("students.csv", "w", newline="", encoding="utf-8") as file:
        writer = csv.writer(file)
        writer.writerows(students)
    print("students.csv created.")
except OSError as error:
    print("CSV error:", error)
