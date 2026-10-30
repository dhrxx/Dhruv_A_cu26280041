# Replace every occurrence of a word and save the updated file.
old = input("Word to replace: ")
new = input("Replacement word: ")
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        content = file.read()
    content = content.replace(old, new)
    with open("sample.txt", "w", encoding="utf-8") as file:
        file.write(content)
    print("File updated successfully.")
except OSError as error:
    print("File error:", error)
