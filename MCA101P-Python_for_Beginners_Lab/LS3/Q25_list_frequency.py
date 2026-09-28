# Q25: Count the frequency of each element in a list.

values = input("Enter integers separated by spaces: ").split()
numbers = []

for value in values:
    numbers.append(int(value))

frequency = {}

for number in numbers:
    if number in frequency:
        frequency[number] += 1
    else:
        frequency[number] = 1

print("Element frequencies:")
for number in frequency:
    print(number, ":", frequency[number])
