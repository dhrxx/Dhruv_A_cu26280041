# Q29: Find the difference between two lists.
# The result contains elements present in the first list but not in the second.

first_values = input("Enter the first list of integers: ").split()
second_values = input("Enter the second list of integers: ").split()

first = []
second = []

for value in first_values:
    first.append(int(value))

for value in second_values:
    second.append(int(value))

difference = []

for number in first:
    found = False
    for item in second:
        if number == item:
            found = True
            break

    already_added = False
    for item in difference:
        if number == item:
            already_added = True
            break

    if not found and not already_added:
        difference.append(number)

print("Difference (first list - second list):", difference)
