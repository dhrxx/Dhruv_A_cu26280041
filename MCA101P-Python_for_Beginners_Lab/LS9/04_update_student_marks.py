# Update a student's marks in a list using roll number.
students = [{"roll": 1, "name": "Asha", "marks": 88},
            {"roll": 2, "name": "Rohan", "marks": 76}]
try:
    roll = int(input("Roll number: "))
    new_marks = float(input("New marks: "))
    for student in students:
        if student["roll"] == roll:
            student["marks"] = new_marks
            print("Updated record:", student)
            break
    else:
        print("Roll number not found.")
except ValueError:
    print("Enter a valid number.")
