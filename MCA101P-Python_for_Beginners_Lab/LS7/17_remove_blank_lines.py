# Remove blank or whitespace-only lines from a file.
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        lines = file.readlines()
    non_blank = [line for line in lines if line.strip()]
    with open("sample.txt", "w", encoding="utf-8") as file:
        file.writelines(non_blank)
    print("Blank lines removed.")
except OSError as error:
    print("File error:", error)
