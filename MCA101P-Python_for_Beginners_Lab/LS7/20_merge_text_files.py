# Merge two text files into a third file.
try:
    with open("first.txt", "r", encoding="utf-8") as file:
        first = file.read()
    with open("second.txt", "r", encoding="utf-8") as file:
        second = file.read()
    with open("merged.txt", "w", encoding="utf-8") as file:
        file.write(first)
        file.write("\n")
        file.write(second)
    print("Files merged into merged.txt.")
except OSError as error:
    print("File error:", error)
