# Q19: Find the most repeated word in a string.

text = input("Enter a string: ")
words = text.split()
frequency = {}

for word in words:
    word = word.lower()
    if word in frequency:
        frequency[word] += 1
    else:
        frequency[word] = 1

if len(words) == 0:
    print("No words were entered.")
else:
    most_repeated = words[0].lower()
    for word in frequency:
        if frequency[word] > frequency[most_repeated]:
            most_repeated = word

    print("Most repeated word:", most_repeated)
    print("Frequency:", frequency[most_repeated])
