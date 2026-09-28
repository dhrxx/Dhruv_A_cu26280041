# Q30: Create a dictionary from a string with characters as keys and frequency as values.

text = input("Enter a string: ")
frequency = {}

for ch in text:
    if ch in frequency:
        frequency[ch] += 1
    else:
        frequency[ch] = 1

print("Character frequency dictionary:")

for ch in frequency:
    if ch == " ":
        print("[space] :", frequency[ch])
    else:
        print(ch, ":", frequency[ch])
