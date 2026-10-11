"""
Lab Sheet 06: Set Operations
Program 19: Create the power set of a given set

Sample Output:
    Power set:
    set()
    {1}
    {2}
    {3}
    {1, 2}
    {1, 3}
    {2, 3}
    {1, 2, 3}
"""

# Problem: Create the power set of a given set
from itertools import combinations
items = {1, 2, 3}
ordered_items = sorted(items)
print("Power set:")
for size in range(len(ordered_items) + 1):
    for combination in combinations(ordered_items, size):
        print(set(combination))
