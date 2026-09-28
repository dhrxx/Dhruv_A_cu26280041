# Q15: Sort a dictionary by keys.

data = {}
count = int(input("Enter number of dictionary entries: "))

for i in range(count):
    key = input("Enter key: ")
    value = input("Enter value: ")
    data[key] = value

keys = []

for key in data:
    keys.append(key)

# Bubble sort keys alphabetically.
for i in range(len(keys) - 1):
    for j in range(len(keys) - 1 - i):
        if keys[j].lower() > keys[j + 1].lower():
            temp = keys[j]
            keys[j] = keys[j + 1]
            keys[j + 1] = temp

sorted_dictionary = {}

for key in keys:
    sorted_dictionary[key] = data[key]

print("Dictionary sorted by keys:", sorted_dictionary)
