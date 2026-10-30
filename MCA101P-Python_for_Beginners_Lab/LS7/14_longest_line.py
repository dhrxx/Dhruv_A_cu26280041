# Display the longest line in a text file.
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        lines = file.readlines()
    if lines:
        longest = max(lines, key=len)
        print("Longest line:", longest.rstrip())
        print("Characters:", len(longest.rstrip("\n")))
    else:
        print("The file is empty.")
except OSError as error:
    print("File error:", error)
