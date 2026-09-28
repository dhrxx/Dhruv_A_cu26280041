# Q4: Extract all uppercase characters from a string.

text = input("Enter a string: ")
uppercase = ""

for ch in text:
    if ch >= "A" and ch <= "Z":
        uppercase += ch

if uppercase:
    print("Uppercase characters:", uppercase)
else:
    print("No uppercase characters found.")
