"""
Lab Sheet 06: Set Operations
Program 18: Remove all elements of one set from another set

Sample Output:
    Result: [1, 3, 5]
"""

# Problem: Remove all elements of one set from another set
first = {1, 2, 3, 4, 5}
second = {2, 4}
first.difference_update(second)
print("Result:", sorted(first))
