# Q12: Check if a substring exists in a given string.

text = input("Enter the main string: ")
substring = input("Enter the substring: ")

if substring in text:
    print("The substring exists in the string.")
else:
    print("The substring does not exist in the string.")
