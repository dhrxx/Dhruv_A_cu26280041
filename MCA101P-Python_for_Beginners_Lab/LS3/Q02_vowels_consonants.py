# Q2: Count the number of vowels and consonants in a string.

text = input("Enter a string: ")
vowels = 0
consonants = 0

for ch in text:
    lower = ch.lower()
    if lower >= "a" and lower <= "z":
        if lower == "a" or lower == "e" or lower == "i" or lower == "o" or lower == "u":
            vowels += 1
        else:
            consonants += 1

print("Number of vowels:", vowels)
print("Number of consonants:", consonants)
