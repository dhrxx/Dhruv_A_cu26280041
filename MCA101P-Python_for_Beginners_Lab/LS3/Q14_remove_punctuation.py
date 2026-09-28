# Q14: Remove all punctuation from a string.

text = input("Enter a string: ")
result = ""

punctuation = ".,!?;:'" + '"' + "-_()[]{}<>/@#$%^&*+=|\\~`"

for ch in text:
    if ch not in punctuation:
        result += ch

print("String without punctuation:", result)
