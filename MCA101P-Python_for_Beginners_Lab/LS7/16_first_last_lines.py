# Display the first and last lines of a text file.
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        lines = file.readlines()
    if lines:
        print("First line:", lines[0].rstrip())
        print("Last line:", lines[-1].rstrip())
    else:
        print("The file is empty.")
except OSError as error:
    print("File error:", error)
