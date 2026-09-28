# Q13: Convert vowels to uppercase and consonants to lowercase.

text = input("Enter a string: ")
result = ""

for ch in text:
    lower = ch.lower()

    if lower == "a" or lower == "e" or lower == "i" or lower == "o" or lower == "u":
        result += lower.upper()
    elif lower >= "a" and lower <= "z":
        result += lower
    else:
        result += ch

print("Converted string:", result)
