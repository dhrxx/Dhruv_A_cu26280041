# Append new content without deleting existing content.
try:
    text = input("Enter content to append: ")
    with open("sample.txt", "a", encoding="utf-8") as file:
        file.write(text + "\n")
    print("Content appended successfully.")
except OSError as error:
    print("File error:", error)
