# Q8: Find pairs of numbers whose sum equals a given number.

values = input("Enter integers separated by spaces: ").split()
target = int(input("Enter the target sum: "))

numbers = []
for value in values:
    numbers.append(int(value))

found = False

print("Pairs with sum", target, ":")

for i in range(len(numbers)):
    for j in range(i + 1, len(numbers)):
        if numbers[i] + numbers[j] == target:
            print("(", numbers[i], ",", numbers[j], ")")
            found = True

if not found:
    print("No matching pair found.")
