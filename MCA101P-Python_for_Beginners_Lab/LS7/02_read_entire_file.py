# Read and display the complete content of a text file.
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        print(file.read())
except FileNotFoundError:
    print("sample.txt not found. Run 01_write_file.py first.")
except OSError as error:
    print("File error:", error)
