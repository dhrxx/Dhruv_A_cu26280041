# Q19: Check if a key exists in a dictionary.

data = {}
count = int(input("Enter number of dictionary entries: "))

for i in range(count):
    key = input("Enter key: ")
    value = input("Enter value: ")
    data[key] = value

key = input("Enter the key to search: ")

if key in data:
    print("The key exists in the dictionary.")
else:
    print("The key does not exist in the dictionary.")
