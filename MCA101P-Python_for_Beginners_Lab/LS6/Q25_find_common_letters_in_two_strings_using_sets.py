"""
Lab Sheet 06: Set Operations
Program 25: Find common letters in two strings using sets

Sample Output:
    Common letters: ['h', 'n', 'o', 'p', 't', 'y']
"""

# Problem: Find common letters in two strings using sets
first = "python"
second = "typhoon"
common = set(first) & set(second)
print("Common letters:", sorted(common))
