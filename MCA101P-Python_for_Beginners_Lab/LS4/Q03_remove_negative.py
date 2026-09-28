# Q3: Remove all negative numbers from a list.

values = input("Enter integers separated by spaces: ").split()
numbers = []

for value in values:
    numbers.append(int(value))

result = []

for number in numbers:
    if number >= 0:
        result.append(number)

print("Original list:", numbers)
print("List without negative numbers:", result)
