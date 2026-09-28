# Q20: Find the sum of all values in a dictionary.

data = {}
count = int(input("Enter number of entries: "))

for i in range(count):
    key = input("Enter key: ")
    value = float(input("Enter numeric value: "))
    data[key] = value

total = 0

for key in data:
    total += data[key]

print("Dictionary:", data)
print("Sum of all values:", total)
