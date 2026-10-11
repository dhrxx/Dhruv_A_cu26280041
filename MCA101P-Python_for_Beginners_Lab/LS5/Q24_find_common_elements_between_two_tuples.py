"""
Lab Sheet 05: Tuple Operations
Program 24: Find common elements between two tuples

Sample Output:
    Common elements: (3, 4)
"""

# Problem: Find common elements between two tuples
first = (1, 2, 3, 4)
second = (3, 4, 5, 6)
common = tuple(item for item in first if item in second)
print("Common elements:", common)
