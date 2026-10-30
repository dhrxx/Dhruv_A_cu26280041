# Accept multiple lines from the user; enter END to finish.
print("Enter text lines. Type END on a separate line to finish.")
try:
    with open("user_notes.txt", "w", encoding="utf-8") as file:
        while True:
            line = input()
            if line == "END":
                break
            file.write(line + "\n")
    print("Your lines were saved in user_notes.txt.")
except OSError as error:
    print("File error:", error)
