# Q27: Find the second largest number in a list.

values = input("Enter integers separated by spaces: ").split()
numbers = []

for value in values:
    numbers.append(int(value))

# Remove duplicates manually so the second largest is distinct.
unique = []
for number in numbers:
    found = False
    for existing in unique:
        if number == existing:
            found = True
            break
    if not found:
        unique.append(number)

if len(unique) < 2:
    print("A second largest distinct number does not exist.")
else:
    # Sort manually in ascending order.
    for i in range(len(unique) - 1):
        for j in range(len(unique) - 1 - i):
            if unique[j] > unique[j + 1]:
                temp = unique[j]
                unique[j] = unique[j + 1]
                unique[j + 1] = temp

    print("Second largest number:", unique[len(unique) - 2])
