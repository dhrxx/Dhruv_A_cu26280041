# Count lines and characters in a text file.
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        content = file.read()
    print("Lines:", len(content.splitlines()))
    print("Characters:", len(content))
except OSError as error:
    print("File error:", error)
