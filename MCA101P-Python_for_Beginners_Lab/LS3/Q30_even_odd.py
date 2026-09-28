# Q30: Separate even and odd numbers from a list.

values = input("Enter integers separated by spaces: ").split()
numbers = []
even = []
odd = []

for value in values:
    numbers.append(int(value))

for number in numbers:
    if number % 2 == 0:
        even.append(number)
    else:
        odd.append(number)

print("Original list:", numbers)
print("Even numbers:", even)
print("Odd numbers:", odd)
