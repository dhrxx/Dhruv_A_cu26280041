"""
Lab Sheet 06: Set Operations
Program 28: Create a frozenset and demonstrate its properties

Sample Output:
    Frozenset: [1, 2, 3]
    Contains 2: True
    Length: 3
    Immutable: add() and remove() are not supported
"""

# Problem: Create a frozenset and demonstrate its properties
items = frozenset([1, 2, 3, 2])
print("Frozenset:", sorted(items))
print("Contains 2:", 2 in items)
print("Length:", len(items))
print("Immutable: add() and remove() are not supported")
