"""
Lab Sheet 05: Tuple Operations
Program 25: Find the difference between two tuples

Sample Output:
    Difference (first - second): (1, 2, 4)
"""

# Problem: Find the difference between two tuples
first = (1, 2, 3, 4, 5)
second = (3, 5)
difference = tuple(item for item in first if item not in second)
print("Difference (first - second):", difference)
