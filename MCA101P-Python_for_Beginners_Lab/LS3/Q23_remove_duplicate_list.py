# Q23: Remove duplicate elements from a list.

values = input("Enter integers separated by spaces: ").split()
numbers = []

for value in values:
    numbers.append(int(value))

unique = []

for number in numbers:
    found = False
    for existing in unique:
        if number == existing:
            found = True
            break
    if not found:
        unique.append(number)

print("Original list:", numbers)
print("List without duplicates:", unique)
