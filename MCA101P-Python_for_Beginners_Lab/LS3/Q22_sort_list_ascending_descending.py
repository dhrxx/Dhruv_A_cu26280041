# Q22: Sort a list of integers in ascending and descending order.

values = input("Enter integers separated by spaces: ").split()
numbers = []

for value in values:
    numbers.append(int(value))

# Bubble sort in ascending order.
for i in range(len(numbers) - 1):
    for j in range(len(numbers) - 1 - i):
        if numbers[j] > numbers[j + 1]:
            temp = numbers[j]
            numbers[j] = numbers[j + 1]
            numbers[j + 1] = temp

ascending = numbers[:]

# Create descending order manually.
descending = []
i = len(numbers) - 1
while i >= 0:
    descending.append(numbers[i])
    i -= 1

print("Ascending order:", ascending)
print("Descending order:", descending)
