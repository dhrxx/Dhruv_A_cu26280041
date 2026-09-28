# Q6: Find the frequency of each character in a string.

text = input("Enter a string: ")
frequency = {}

for ch in text:
    if ch in frequency:
        frequency[ch] += 1
    else:
        frequency[ch] = 1

print("Character frequencies:")
for ch in frequency:
    if ch == " ":
        print("[space] :", frequency[ch])
    else:
        print(ch, ":", frequency[ch])
