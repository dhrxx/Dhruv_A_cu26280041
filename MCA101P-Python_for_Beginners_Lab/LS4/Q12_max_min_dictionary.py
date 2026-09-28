# Q12: Find maximum and minimum marks from a dictionary.

data = {}
count = int(input("Enter number of students: "))

for i in range(count):
    name = input("Enter student name: ")
    marks = float(input("Enter marks: "))
    data[name] = marks

if len(data) == 0:
    print("The dictionary is empty.")
else:
    first = True

    for name in data:
        if first:
            maximum = data[name]
            minimum = data[name]
            max_name = name
            min_name = name
            first = False
        else:
            if data[name] > maximum:
                maximum = data[name]
                max_name = name
            if data[name] < minimum:
                minimum = data[name]
                min_name = name

    print("Maximum marks:", maximum, "-", max_name)
    print("Minimum marks:", minimum, "-", min_name)
