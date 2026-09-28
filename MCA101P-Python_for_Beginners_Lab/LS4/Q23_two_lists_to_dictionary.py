# Q23: Convert two lists into a dictionary.

keys = input("Enter keys separated by spaces: ").split()
values = input("Enter values separated by spaces: ").split()

if len(keys) != len(values):
    print("Both lists must have the same number of elements.")
else:
    data = {}

    for i in range(len(keys)):
        data[keys[i]] = values[i]

    print("Dictionary:", data)
