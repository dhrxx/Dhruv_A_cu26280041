# Q4: Find the product of all elements in a list.

values = input("Enter numbers separated by spaces: ").split()

if len(values) == 0:
    print("The list is empty.")
else:
    product = 1
    numbers = []

    for value in values:
        number = float(value)
        numbers.append(number)
        product *= number

    print("List:", numbers)
    print("Product:", product)
