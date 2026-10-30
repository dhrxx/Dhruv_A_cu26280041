# Count exact occurrences of a word, ignoring letter case and punctuation.
import re
word = input("Enter the word to count: ").strip()
try:
    with open("sample.txt", "r", encoding="utf-8") as file:
        words = re.findall(r"\b[\w']+\b", file.read().lower())
    print(f"'{word}' occurs {words.count(word.lower())} time(s).")
except OSError as error:
    print("File error:", error)
