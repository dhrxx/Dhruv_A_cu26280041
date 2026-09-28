# Q25: Count word frequency in a paragraph using a dictionary.

paragraph = input("Enter a paragraph: ")

words = paragraph.split()
frequency = {}

for word in words:
    # Remove common punctuation from the beginning and end of a word.
    cleaned = ""
    for ch in word:
        if ch not in ".,!?;:'\"()[]{}":
            cleaned += ch

    cleaned = cleaned.lower()

    if cleaned != "":
        if cleaned in frequency:
            frequency[cleaned] += 1
        else:
            frequency[cleaned] = 1

print("Word frequencies:")
for word in frequency:
    print(word, ":", frequency[word])
