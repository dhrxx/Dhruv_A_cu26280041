# Q29: Find common keys between two dictionaries.

first = {}
second = {}

count1 = int(input("Enter number of entries for first dictionary: "))
for i in range(count1):
    key = input("Enter key: ")
    value = input("Enter value: ")
    first[key] = value

count2 = int(input("Enter number of entries for second dictionary: "))
for i in range(count2):
    key = input("Enter key: ")
    value = input("Enter value: ")
    second[key] = value

common = []

for key in first:
    if key in second:
        common.append(key)

print("Common keys:", common)
