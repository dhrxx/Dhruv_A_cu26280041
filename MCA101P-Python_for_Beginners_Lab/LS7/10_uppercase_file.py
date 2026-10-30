# Read a file and display its content in uppercase.
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        content = file.read()
    print(content.upper())
except OSError as error:
    print("File error:", error)
