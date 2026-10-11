"""
Lab Sheet 06: Set Operations
Program 27: Check if two strings are anagrams using sets

Sample Output:
    Are anagrams: True
"""

# Problem: Check if two strings are anagrams using sets
first = "listen"
second = "silent"
are_anagrams = sorted(first.replace(" ", "").lower()) == sorted(second.replace(" ", "").lower())
print("Are anagrams:", are_anagrams)
