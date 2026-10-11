"""
Lab Sheet 05: Tuple Operations
Program 19: Remove duplicate elements from a tuple

Sample Output:
    Tuple without duplicates: (1, 2, 3, 4)
"""

# Problem: Remove duplicate elements from a tuple
items = (1, 2, 2, 3, 1, 4, 3)
unique_items = tuple(dict.fromkeys(items))
print("Tuple without duplicates:", unique_items)
