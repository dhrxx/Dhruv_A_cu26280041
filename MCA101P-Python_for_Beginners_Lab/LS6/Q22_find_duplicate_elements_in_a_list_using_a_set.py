"""
Lab Sheet 06: Set Operations
Program 22: Find duplicate elements in a list using a set

Sample Output:
    Duplicates: [1, 2]
"""

# Problem: Find duplicate elements in a list using a set
items = [1, 2, 3, 2, 4, 1, 5, 2]
seen = set()
duplicates = set()
for item in items:
    if item in seen:
        duplicates.add(item)
    seen.add(item)
print("Duplicates:", sorted(duplicates))
