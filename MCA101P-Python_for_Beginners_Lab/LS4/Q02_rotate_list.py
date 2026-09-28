# Q2: Rotate a list by n positions.

values = input("Enter elements separated by spaces: ").split()
n = int(input("Enter number of positions to rotate: "))

if len(values) == 0:
    print("The list is empty.")
else:
    n = n % len(values)
    rotated = []

    # Right rotation by n positions.
    for i in range(len(values) - n, len(values)):
        rotated.append(values[i])

    for i in range(0, len(values) - n):
        rotated.append(values[i])

    print("Original list:", values)
    print("Rotated list:", rotated)
