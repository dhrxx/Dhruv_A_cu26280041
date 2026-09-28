# Q10: Find the longest word in a string.

text = input("Enter a string: ")
words = text.split()

if len(words) == 0:
    print("No words were entered.")
else:
    longest = words[0]
    for word in words:
        if len(word) > len(longest):
            longest = word

    print("Longest word:", longest)
    print("Length:", len(longest))
