# Q16: Count digits, alphabets, and special characters.

text = input("Enter a string: ")
digits = 0
alphabets = 0
special = 0

for ch in text:
    if ch >= "0" and ch <= "9":
        digits += 1
    elif (ch >= "A" and ch <= "Z") or (ch >= "a" and ch <= "z"):
        alphabets += 1
    else:
        special += 1

print("Digits:", digits)
print("Alphabets:", alphabets)
print("Special characters:", special)
