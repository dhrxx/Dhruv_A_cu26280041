# Q14: Merge two dictionaries into one.

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

merged = {}

for key in first:
    merged[key] = first[key]

for key in second:
    merged[key] = second[key]

print("First dictionary:", first)
print("Second dictionary:", second)
print("Merged dictionary:", merged)
