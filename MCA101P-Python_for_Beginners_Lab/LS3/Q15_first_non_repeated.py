# Q15: Find the first non-repeated character in a string.

text = input("Enter a string: ")
frequency = {}

for ch in text:
    if ch in frequency:
        frequency[ch] += 1
    else:
        frequency[ch] = 1

found = False

for ch in text:
    if frequency[ch] == 1:
        print("First non-repeated character:", ch)
        found = True
        break

if not found:
    print("No non-repeated character found.")
