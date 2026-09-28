# Q11: Create a dictionary of student names and marks and display it.

students = {}
count = int(input("Enter number of students: "))

if count < 0:
    print("Number of students cannot be negative.")
else:
    for i in range(count):
        name = input("Enter student name: ")
        marks = float(input("Enter marks for " + name + ": "))
        students[name] = marks

    print("\nStudent Marks:")
    for name in students:
        print(name, ":", students[name])
