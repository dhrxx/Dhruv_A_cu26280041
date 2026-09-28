# Q17: Print all substrings of a string.

text = input("Enter a string: ")

print("All substrings:")
for start in range(len(text)):
    substring = ""
    for end in range(start, len(text)):
        substring += text[end]
        print(substring)
