# Combine CSV files listed below into one CSV file, keeping one header.
import csv
input_files = ["students.csv", "students_extra.csv"]
try:
    combined = []
    header = None
    for filename in input_files:
        with open(filename, "r", newline="", encoding="utf-8") as file:
            reader = csv.reader(file)
            current_header = next(reader, None)
            if current_header and header is None:
                header = current_header
            combined.extend(row for row in reader if row)
    with open("combined_students.csv", "w", newline="", encoding="utf-8") as file:
        writer = csv.writer(file)
        if header:
            writer.writerow(header)
        writer.writerows(combined)
    print("Combined data saved to combined_students.csv.")
except OSError as error:
    print("CSV error:", error)
