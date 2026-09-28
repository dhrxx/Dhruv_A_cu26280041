# Q8: Convert a string into title case without using built-in title().

text = input("Enter a string: ")
result = ""
new_word = True

for ch in text:
    if ch == " ":
        result += ch
        new_word = True
    else:
        if new_word:
            if ch >= "a" and ch <= "z":
                result += chr(ord(ch) - 32)
            else:
                result += ch
            new_word = False
        else:
            if ch >= "A" and ch <= "Z":
                result += chr(ord(ch) + 32)
            else:
                result += ch

print("Title case:", result)
