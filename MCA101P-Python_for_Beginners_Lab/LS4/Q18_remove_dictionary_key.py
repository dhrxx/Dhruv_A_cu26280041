# Q18: Remove a key from a dictionary.

data = {}
count = int(input("Enter number of dictionary entries: "))

for i in range(count):
    key = input("Enter key: ")
    value = input("Enter value: ")
    data[key] = value

key = input("Enter the key to remove: ")

if key in data:
    del data[key]
    print("Updated dictionary:", data)
else:
    print("Key does not exist.")
