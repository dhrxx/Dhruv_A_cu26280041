# Q26: Check if two dictionaries are equal.

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

equal = True

if len(first) != len(second):
    equal = False
else:
    for key in first:
        if key not in second or first[key] != second[key]:
            equal = False
            break

if equal:
    print("The dictionaries are equal.")
else:
    print("The dictionaries are not equal.")
