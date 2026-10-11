"""
Lab Sheet 05: Tuple Operations
Program 26: Convert a tuple of tuples into a dictionary

Sample Output:
    Dictionary: {'name': 'Dhruv', 'course': 'MCA', 'semester': 1}
"""

# Problem: Convert a tuple of tuples into a dictionary
pairs = (("name", "Dhruv"), ("course", "MCA"), ("semester", 1))
result = dict(pairs)
print("Dictionary:", result)
