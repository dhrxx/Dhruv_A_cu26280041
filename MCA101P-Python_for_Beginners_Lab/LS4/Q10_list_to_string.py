# Q10: Convert a list into a string without using join().

values = input("Enter words separated by spaces: ").split()
result = ""

for i in range(len(values)):
    result += values[i]
    if i < len(values) - 1:
        result += " "

print("String:", result)
