"""
Lab Sheet 06: Set Operations
Program 23: Remove duplicates from a list using a set

Sample Output:
    List without duplicates: [1, 2, 3, 4]
"""

# Problem: Remove duplicates from a list using a set
items = [1, 2, 2, 3, 1, 4, 3]
unique_items = list(dict.fromkeys(items))
print("List without duplicates:", unique_items)
