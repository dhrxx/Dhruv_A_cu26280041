# Read a text file one line at a time.
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        for line_number, line in enumerate(file, start=1):
            print(f"{line_number}: {line.rstrip()}")
except OSError as error:
    print("File error:", error)
