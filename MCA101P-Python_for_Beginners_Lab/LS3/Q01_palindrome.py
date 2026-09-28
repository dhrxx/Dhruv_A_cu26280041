# Q1: Check if a string is a palindrome.

text = input("Enter a string: ")

# Reverse the string manually without using slicing.
reverse = ""
i = len(text) - 1
while i >= 0:
    reverse += text[i]
    i -= 1

if text == reverse:
    print("The string is a palindrome.")
else:
    print("The string is not a palindrome.")
