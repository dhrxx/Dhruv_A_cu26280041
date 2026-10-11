"""
Lab Sheet 05: Tuple Operations
Program 27: Unzip a list of tuples into individual lists

Sample Output:
    Numbers: [1, 2, 3]
    Names: ['Alice', 'Bob', 'Charlie']
"""

# Problem: Unzip a list of tuples into individual lists
pairs = [(1, "Alice"), (2, "Bob"), (3, "Charlie")]
numbers, names = zip(*pairs)
print("Numbers:", list(numbers))
print("Names:", list(names))
