# Append one student record to an existing CSV file.
import csv
try:
    with open("students.csv", "a", newline="", encoding="utf-8") as file:
        writer = csv.writer(file)
        writer.writerow([4, "Vivek", 81])
    print("New record appended.")
except OSError as error:
    print("CSV error:", error)
