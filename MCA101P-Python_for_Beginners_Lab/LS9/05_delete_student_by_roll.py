# Delete a student record by roll number.
students = [{"roll": 1, "name": "Asha", "marks": 88},
            {"roll": 2, "name": "Rohan", "marks": 76}]
try:
    roll = int(input("Roll number to delete: "))
    before = len(students)
    students = [s for s in students if s["roll"] != roll]
    print("Deleted." if len(students) < before else "Roll number not found.")
    print("Remaining records:", students)
except ValueError:
    print("Roll number must be numeric.")
