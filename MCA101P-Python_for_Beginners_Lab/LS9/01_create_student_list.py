# Create a list of student records (Create operation).
students = []
count = int(input("How many students? "))
for _ in range(count):
    students.append({
        "roll": int(input("Roll number: ")),
        "name": input("Name: ").strip(),
        "marks": float(input("Marks: "))
    })
print("Created student records:", students)
