"""
Lab Sheet 05: Tuple Operations
Program 28: Find repeated items in a tuple

Sample Output:
    Repeated items: (1, 2)
"""

# Problem: Find repeated items in a tuple
items = (1, 2, 3, 2, 4, 1, 5, 2)
repeated = tuple(item for item in dict.fromkeys(items) if items.count(item) > 1)
print("Repeated items:", repeated)
