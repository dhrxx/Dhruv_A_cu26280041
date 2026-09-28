# Q20: Check if two strings are rotations of each other.

first = input("Enter the first string: ")
second = input("Enter the second string: ")

if len(first) == len(second) and second in (first + first):
    print("The strings are rotations of each other.")
else:
    print("The strings are not rotations of each other.")
