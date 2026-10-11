"""
Lab Sheet 06: Set Operations
Program 20: Find the Cartesian product of two sets

Sample Output:
    Cartesian product: [(1, 'a'), (1, 'b'), (2, 'a'), (2, 'b')]
"""

# Problem: Find the Cartesian product of two sets
first = {1, 2}
second = {"a", "b"}
product = {(x, y) for x in first for y in second}
print("Cartesian product:", sorted(product))
