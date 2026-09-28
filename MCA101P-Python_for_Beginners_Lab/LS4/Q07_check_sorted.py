# Q7: Check if a list is sorted or not.

values = input("Enter integers separated by spaces: ").split()
numbers = []

for value in values:
    numbers.append(int(value))

ascending = True

for i in range(len(numbers) - 1):
    if numbers[i] > numbers[i + 1]:
        ascending = False
        break

if ascending:
    print("The list is sorted in ascending order.")
else:
    print("The list is not sorted in ascending order.")
