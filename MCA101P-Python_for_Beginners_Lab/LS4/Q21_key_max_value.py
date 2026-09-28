# Q21: Find the key with the maximum value in a dictionary.

data = {}
count = int(input("Enter number of entries: "))

for i in range(count):
    key = input("Enter key: ")
    value = float(input("Enter numeric value: "))
    data[key] = value

if len(data) == 0:
    print("The dictionary is empty.")
else:
    first = True

    for key in data:
        if first:
            max_key = key
            max_value = data[key]
            first = False
        elif data[key] > max_value:
            max_key = key
            max_value = data[key]

    print("Key with maximum value:", max_key)
    print("Maximum value:", max_value)
