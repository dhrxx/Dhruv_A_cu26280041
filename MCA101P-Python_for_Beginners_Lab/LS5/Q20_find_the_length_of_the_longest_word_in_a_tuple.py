"""
Lab Sheet 05: Tuple Operations
Program 20: Find the length of the longest word in a tuple

Sample Output:
    Length of longest word: 9
"""

# Problem: Find the length of the longest word in a tuple
words = ("cat", "elephant", "tiger", "butterfly")
longest_length = max(len(word) for word in words)
print("Length of longest word:", longest_length)
