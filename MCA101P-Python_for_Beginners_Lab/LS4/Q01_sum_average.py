# Q1: Find the sum and average of elements in a list.

values = input("Enter integers separated by spaces: ").split()
numbers = []

for value in values:
    numbers.append(float(value))

if len(numbers) == 0:
    print("The list is empty.")
else:
    total = 0
    for number in numbers:
        total += number

    average = total / len(numbers)

    print("List:", numbers)
    print("Sum:", total)
    print("Average:", average)
