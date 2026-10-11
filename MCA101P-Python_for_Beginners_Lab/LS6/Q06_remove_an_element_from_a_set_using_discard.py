"""
Lab Sheet 06: Set Operations
Program 06: Remove an element from a set using discard()

Sample Output:
    Updated set: [1, 2, 4]
"""

# Problem: Remove an element from a set using discard()
items = {1, 2, 3, 4}
items.discard(3)
print("Updated set:", sorted(items))
