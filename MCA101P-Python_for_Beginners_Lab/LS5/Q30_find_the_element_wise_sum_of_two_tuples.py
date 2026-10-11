"""
Lab Sheet 05: Tuple Operations
Program 30: Find the element-wise sum of two tuples

Sample Output:
    Element-wise sum: (11, 22, 33, 44)
"""

# Problem: Find the element-wise sum of two tuples
first = (1, 2, 3, 4)
second = (10, 20, 30, 40)
result = tuple(a + b for a, b in zip(first, second))
print("Element-wise sum:", result)
