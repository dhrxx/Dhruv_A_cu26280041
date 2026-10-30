# Search for a word in a file (case-insensitive).
word = input("Enter word to search: ").strip().lower()
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        content = file.read().lower()
    print("Word found." if word and word in content else "Word not found.")
except OSError as error:
    print("File error:", error)
