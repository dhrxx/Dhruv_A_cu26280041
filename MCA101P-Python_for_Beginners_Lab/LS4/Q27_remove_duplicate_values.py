# Q27: Remove duplicate values from a dictionary.

data = {}
count = int(input("Enter number of entries: "))

for i in range(count):
    key = input("Enter key: ")
    value = input("Enter value: ")
    data[key] = value

result = {}
seen_values = []

for key in data:
    value = data[key]
    found = False

    for existing in seen_values:
        if value == existing:
            found = True
            break

    if not found:
        result[key] = value
        seen_values.append(value)

print("Original dictionary:", data)
print("Dictionary after removing duplicate values:", result)
