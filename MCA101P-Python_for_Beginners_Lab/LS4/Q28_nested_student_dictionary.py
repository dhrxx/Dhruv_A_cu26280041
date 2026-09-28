# Q28: Create a nested dictionary for student details (Name, Roll, Marks).

students = {}
count = int(input("Enter number of students: "))

for i in range(count):
    roll = input("Enter roll number: ")
    name = input("Enter name: ")
    marks = float(input("Enter marks: "))

    students[roll] = {
        "Name": name,
        "Roll": roll,
        "Marks": marks
    }

print("\nStudent details:")
for roll in students:
    print("Roll:", students[roll]["Roll"])
    print("Name:", students[roll]["Name"])
    print("Marks:", students[roll]["Marks"])
    print()
