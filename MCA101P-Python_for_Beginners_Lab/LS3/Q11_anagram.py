# Q11: Check if two strings are anagrams of each other.

first = input("Enter the first string: ")
second = input("Enter the second string: ")

# Ignore spaces and letter case.
a = ""
b = ""

for ch in first:
    if ch != " ":
        a += ch.lower()

for ch in second:
    if ch != " ":
        b += ch.lower()

if len(a) != len(b):
    print("The strings are not anagrams.")
else:
    frequency = {}
    for ch in a:
        if ch in frequency:
            frequency[ch] += 1
        else:
            frequency[ch] = 1

    for ch in b:
        if ch in frequency:
            frequency[ch] -= 1
        else:
            frequency[ch] = -1

    anagram = True
    for ch in frequency:
        if frequency[ch] != 0:
            anagram = False
            break

    if anagram:
        print("The strings are anagrams.")
    else:
        print("The strings are not anagrams.")
