# Perform CRUD operations on an employee dictionary.
employees = {}
while True:
    print("\\n1.Add 2.View 3.Update salary 4.Delete 5.Exit")
    choice = input("Choose: ")
    if choice == "1":
        emp_id = input("Employee ID: ")
        employees[emp_id] = {"name": input("Name: "),
                             "salary": float(input("Salary: "))}
        print("Employee added.")
    elif choice == "2":
        print(employees or "No employees.")
    elif choice == "3":
        emp_id = input("Employee ID: ")
        if emp_id in employees:
            employees[emp_id]["salary"] = float(input("New salary: "))
            print("Salary updated.")
        else: print("Employee not found.")
    elif choice == "4":
        emp_id = input("Employee ID: ")
        print("Deleted." if employees.pop(emp_id, None) is not None else "Not found.")
    elif choice == "5": break
    else: print("Invalid choice.")
