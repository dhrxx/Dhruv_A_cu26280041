# Q24: Invert a dictionary (keys become values and values become keys).

data = {}
count = int(input("Enter number of entries: "))

for i in range(count):
    key = input("Enter key: ")
    value = input("Enter value: ")
    data[key] = value

inverted = {}

for key in data:
    value = data[key]
    inverted[value] = key

print("Original dictionary:", data)
print("Inverted dictionary:", inverted)
