# Read numbers separated by spaces or new lines and calculate sum and average.
try:
    with open("numbers.txt", "r", encoding="utf-8") as file:
        numbers = [float(value) for value in file.read().split()]
    if numbers:
        print("Sum:", sum(numbers))
        print("Average:", sum(numbers) / len(numbers))
    else:
        print("The file contains no numbers.")
except FileNotFoundError:
    print("Create numbers.txt with numbers separated by spaces or new lines.")
except ValueError:
    print("The file contains a value that is not a number.")
except OSError as error:
    print("File error:", error)
