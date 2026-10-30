# Sort all words alphabetically and save them to a new file.
import re
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        words = re.findall(r"\b[\w']+\b", file.read())
    words.sort(key=str.lower)
    with open("sorted_words.txt", "w", encoding="utf-8") as file:
        file.write("\n".join(words))
    print("Sorted words saved to sorted_words.txt.")
except OSError as error:
    print("File error:", error)
