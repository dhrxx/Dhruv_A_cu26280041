# Q7: Reverse a string without using slicing.

text = input("Enter a string: ")
reverse = ""

i = len(text) - 1
while i >= 0:
    reverse += text[i]
    i -= 1

print("Reversed string:", reverse)
