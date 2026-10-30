# Search for a student by name in a list.
students = [{"roll": 1, "name": "Asha", "marks": 88},
            {"roll": 2, "name": "Rohan", "marks": 76}]
name = input("Enter name to search: ").strip().casefold()
matches = [s for s in students if s["name"].casefold() == name]
print(*matches, sep="\\n") if matches else print("Student not found.")
