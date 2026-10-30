# Copy the content of one text file to another.
source, destination = "sample.txt", "sample_copy.txt"
try:
    with open(source, "r", encoding="utf-8") as src:
        content = src.read()
    with open(destination, "w", encoding="utf-8") as dst:
        dst.write(content)
    print(f"Copied {source} to {destination}.")
except OSError as error:
    print("File error:", error)
