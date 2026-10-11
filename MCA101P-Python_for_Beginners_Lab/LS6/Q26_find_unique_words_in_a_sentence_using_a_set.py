"""
Lab Sheet 06: Set Operations
Program 26: Find unique words in a sentence using a set

Sample Output:
    Unique words: ['and', 'coding', 'fun', 'is', 'makes', 'powerful', 'python']
"""

# Problem: Find unique words in a sentence using a set
sentence = "Python makes coding fun and Python is powerful"
words = set(sentence.lower().split())
print("Unique words:", sorted(words))
