# Q26: Merge two lists into one.

first_values = input("Enter the first list of integers: ").split()
second_values = input("Enter the second list of integers: ").split()

first = []
second = []

for value in first_values:
    first.append(int(value))

for value in second_values:
    second.append(int(value))

merged = []

for number in first:
    merged.append(number)

for number in second:
    merged.append(number)

print("First list:", first)
print("Second list:", second)
print("Merged list:", merged)
