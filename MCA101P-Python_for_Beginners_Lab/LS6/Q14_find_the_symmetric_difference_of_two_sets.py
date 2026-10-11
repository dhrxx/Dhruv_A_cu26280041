"""
Lab Sheet 06: Set Operations
Program 14: Find the symmetric difference of two sets

Sample Output:
    Symmetric difference: [1, 2, 4, 5]
"""

# Problem: Find the symmetric difference of two sets
first = {1, 2, 3}
second = {3, 4, 5}
print("Symmetric difference:", sorted(first ^ second))
