# Save student records to a text file and read them back.
students = [{"roll": 1, "name": "Asha", "marks": 88},
            {"roll": 2, "name": "Rohan", "marks": 76}]
try:
    with open("student_records.txt", "w", encoding="utf-8") as file:
        for student in students:
            file.write(f'{student["roll"]},{student["name"]},{student["marks"]}\\n')
    with open("student_records.txt", "r", encoding="utf-8") as file:
        print(file.read())
except OSError as error:
    print("File error:", error)
