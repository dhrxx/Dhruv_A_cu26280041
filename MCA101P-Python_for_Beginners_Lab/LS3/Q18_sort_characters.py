# Q18: Sort the characters of a string alphabetically.

text = input("Enter a string: ")
characters = []

for ch in text:
    characters.append(ch)

# Bubble sort is used instead of the built-in sort() function.
n = len(characters)
for i in range(n - 1):
    for j in range(n - 1 - i):
        if characters[j].lower() > characters[j + 1].lower():
            temp = characters[j]
            characters[j] = characters[j + 1]
            characters[j + 1] = temp

result = ""
for ch in characters:
    result += ch

print("Characters in alphabetical order:", result)
