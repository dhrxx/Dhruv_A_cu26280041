# Q22: Create a dictionary with numbers as keys and squares as values.

n = int(input("Enter n: "))

if n < 1:
    print("Please enter a positive integer.")
else:
    squares = {}

    for number in range(1, n + 1):
        squares[number] = number * number

    print("Dictionary:", squares)
