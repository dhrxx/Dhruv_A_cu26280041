# Q3: Replace all spaces in a string with underscores.

text = input("Enter a string: ")
result = ""

for ch in text:
    if ch == " ":
        result += "_"
    else:
        result += ch

print("String after replacement:", result)
