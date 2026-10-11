"""
Lab Sheet 05: Tuple Operations
Program 29: Check if all elements in a tuple are the same

Sample Output:
    All elements are the same: True
"""

# Problem: Check if all elements in a tuple are the same
items = (7, 7, 7, 7)
print("All elements are the same:", len(set(items)) <= 1)
