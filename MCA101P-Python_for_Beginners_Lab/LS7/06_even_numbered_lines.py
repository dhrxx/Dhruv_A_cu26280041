# Display only even-numbered lines (line numbering starts at 1).
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        for number, line in enumerate(file, start=1):
            if number % 2 == 0:
                print(line.rstrip())
except OSError as error:
    print("File error:", error)
