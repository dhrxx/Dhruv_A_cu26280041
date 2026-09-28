# Q16: Sort a dictionary by values.

data = {}
count = int(input("Enter number of dictionary entries: "))

for i in range(count):
    key = input("Enter key: ")
    value = float(input("Enter numeric value: "))
    data[key] = value

items = []

for key in data:
    items.append([key, data[key]])

# Bubble sort by dictionary values.
for i in range(len(items) - 1):
    for j in range(len(items) - 1 - i):
        if items[j][1] > items[j + 1][1]:
            temp = items[j]
            items[j] = items[j + 1]
            items[j + 1] = temp

sorted_dictionary = {}

for item in items:
    sorted_dictionary[item[0]] = item[1]

print("Dictionary sorted by values:", sorted_dictionary)
