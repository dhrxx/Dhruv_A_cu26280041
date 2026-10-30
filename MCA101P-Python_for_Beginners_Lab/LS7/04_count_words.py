# Count words in a text file.
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        words = file.read().split()
    print("Total words:", len(words))
except OSError as error:
    print("File error:", error)
