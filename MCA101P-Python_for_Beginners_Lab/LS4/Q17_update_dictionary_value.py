# Q17: Update the value of a key in a dictionary.

data = {}
count = int(input("Enter number of dictionary entries: "))

for i in range(count):
    key = input("Enter key: ")
    value = input("Enter value: ")
    data[key] = value

key = input("Enter the key to update: ")

if key in data:
    new_value = input("Enter the new value: ")
    data[key] = new_value
    print("Updated dictionary:", data)
else:
    print("Key does not exist.")
