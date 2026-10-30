# File-based CRUD for student records stored as one CSV-style line each.
FILE = "crud_students.txt"

def read_records():
    try:
        with open(FILE, "r", encoding="utf-8") as file:
            return [line.strip().split(",", 2) for line in file if line.strip()]
    except FileNotFoundError:
        return []

def save_records(records):
    with open(FILE, "w", encoding="utf-8") as file:
        for record in records:
            file.write(",".join(record) + "\\n")

def main():
    while True:
        print("\\n1.Add 2.View 3.Modify name 4.Delete 5.Exit")
        choice = input("Choose: ")
        records = read_records()
        if choice == "1":
            records.append([input("Roll: "), input("Name: "), input("Marks: ")])
            save_records(records); print("Record added.")
        elif choice == "2":
            for record in records: print(record)
            if not records: print("No records.")
        elif choice == "3":
            roll = input("Roll to modify: ")
            for record in records:
                if record[0] == roll:
                    record[1] = input("New name: ")
                    save_records(records); print("Updated."); break
            else: print("Record not found.")
        elif choice == "4":
            roll = input("Roll to delete: ")
            remaining = [r for r in records if r[0] != roll]
            save_records(remaining)
            print("Deleted." if len(remaining) < len(records) else "Not found.")
        elif choice == "5": break
        else: print("Invalid choice.")

if __name__ == "__main__":
    try: main()
    except OSError as error: print("File error:", error)
