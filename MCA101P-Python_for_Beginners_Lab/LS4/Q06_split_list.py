# Q6: Split a list into two halves.

values = input("Enter elements separated by spaces: ").split()

if len(values) == 0:
    print("The list is empty.")
else:
    middle = len(values) // 2

    first_half = []
    second_half = []

    for i in range(middle):
        first_half.append(values[i])

    for i in range(middle, len(values)):
        second_half.append(values[i])

    print("First half:", first_half)
    print("Second half:", second_half)

    if len(values) % 2 != 0:
        print("The extra element is placed in the second half.")
