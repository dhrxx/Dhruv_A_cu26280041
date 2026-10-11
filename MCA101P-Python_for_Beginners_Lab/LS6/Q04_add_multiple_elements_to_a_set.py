"""
Lab Sheet 06: Set Operations
Program 04: Add multiple elements to a set

Sample Output:
    Updated set: [1, 2, 3, 4, 5, 6]
"""

# Problem: Add multiple elements to a set
items = {1, 2, 3}
items.update([4, 5, 6])
print("Updated set:", sorted(items))
