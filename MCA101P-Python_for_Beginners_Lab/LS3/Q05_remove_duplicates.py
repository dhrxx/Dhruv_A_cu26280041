# Q5: Remove all duplicate characters from a string.

text = input("Enter a string: ")
result = ""

for ch in text:
    found = False
    for existing in result:
        if ch == existing:
            found = True
            break
    if not found:
        result += ch

print("String after removing duplicates:", result)
