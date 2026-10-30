# Store unique words in another file, preserving first-seen order.
import re
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        words = re.findall(r"\b[\w']+\b", file.read())
    unique_words = list(dict.fromkeys(word.lower() for word in words))
    with open("unique_words.txt", "w", encoding="utf-8") as file:
        file.write("\n".join(unique_words))
    print("Unique words saved to unique_words.txt.")
except OSError as error:
    print("File error:", error)
