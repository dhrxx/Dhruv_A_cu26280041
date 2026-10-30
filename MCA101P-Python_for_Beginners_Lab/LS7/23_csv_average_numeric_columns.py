# Calculate the average for every numeric column in a CSV file with headers.
import csv
from collections import defaultdict
totals, counts = defaultdict(float), defaultdict(int)
try:
    with open("students.csv", "r", newline="", encoding="utf-8") as file:
        reader = csv.DictReader(file)
        for row in reader:
            for column, value in row.items():
                try:
                    totals[column] += float(value)
                    counts[column] += 1
                except (ValueError, TypeError):
                    pass
    for column in totals:
        print(f"{column} average: {totals[column] / counts[column]:.2f}")
except OSError as error:
    print("CSV error:", error)
