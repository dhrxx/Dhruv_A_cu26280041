# Q28: Find common elements in two lists.

first_values = input("Enter the first list of integers: ").split()
second_values = input("Enter the second list of integers: ").split()

first = []
second = []

for value in first_values:
    first.append(int(value))

for value in second_values:
    second.append(int(value))

common = []

for number in first:
    found_in_second = False
    for item in second:
        if number == item:
            found_in_second = True
            break

    already_added = False
    for item in common:
        if number == item:
            already_added = True
            break

    if found_in_second and not already_added:
        common.append(number)

print("Common elements:", common)
