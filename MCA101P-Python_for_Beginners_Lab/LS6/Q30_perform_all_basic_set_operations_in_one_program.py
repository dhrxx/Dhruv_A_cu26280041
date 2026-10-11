"""
Lab Sheet 06: Set Operations
Program 30: Perform all basic set operations in one program

Sample Output:
    Union: [1, 2, 3, 4, 5, 6]
    Intersection: [3, 4]
    Difference (first - second): [1, 2]
    Symmetric difference: [1, 2, 5, 6]
"""

# Problem: Perform all basic set operations in one program
first = {1, 2, 3, 4}
second = {3, 4, 5, 6}
print("Union:", sorted(first | second))
print("Intersection:", sorted(first & second))
print("Difference (first - second):", sorted(first - second))
print("Symmetric difference:", sorted(first ^ second))
