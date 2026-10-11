"""
Lab Sheet 05: Tuple Operations
Program 23: Swap two tuples

Sample Output:
    First tuple: ('a', 'b', 'c')
    Second tuple: (1, 2, 3)
"""

# Problem: Swap two tuples
first = (1, 2, 3)
second = ("a", "b", "c")
first, second = second, first
print("First tuple:", first)
print("Second tuple:", second)
