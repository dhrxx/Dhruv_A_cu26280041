"""
Lab Sheet 05: Tuple Operations
Program 22: Flatten a nested tuple

Sample Output:
    Flattened tuple: (1, 2, 3, 4, 5, 6)
"""

# Problem: Flatten a nested tuple
nested = ((1, 2), (3, 4), (5, 6))
flat = tuple(item for group in nested for item in group)
print("Flattened tuple:", flat)
