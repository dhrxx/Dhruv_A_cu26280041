# Q21: Find the largest and smallest elements in a list.

values = input("Enter integers separated by spaces: ").split()

if len(values) == 0:
    print("The list is empty.")
else:
    numbers = []
    for value in values:
        numbers.append(int(value))

    largest = numbers[0]
    smallest = numbers[0]

    for number in numbers:
        if number > largest:
            largest = number
        if number < smallest:
            smallest = number

    print("List:", numbers)
    print("Largest element:", largest)
    print("Smallest element:", smallest)
