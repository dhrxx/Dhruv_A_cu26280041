# Q24: Reverse a list without using built-in reverse().

values = input("Enter integers separated by spaces: ").split()
numbers = []

for value in values:
    numbers.append(int(value))

reverse = []
i = len(numbers) - 1

while i >= 0:
    reverse.append(numbers[i])
    i -= 1

print("Original list:", numbers)
print("Reversed list:", reverse)
