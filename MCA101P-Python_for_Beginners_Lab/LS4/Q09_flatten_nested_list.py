# Q9: Flatten a nested list.
# Enter nested lists using Python-style input, for example: [[1, 2], [3, 4], [5]]

import ast

data = input("Enter a nested list, e.g. [[1, 2], [3, 4]]: ")

try:
    nested = ast.literal_eval(data)

    if not isinstance(nested, list):
        print("Please enter a valid list.")
    else:
        flattened = []

        for item in nested:
            if isinstance(item, list):
                for value in item:
                    flattened.append(value)
            else:
                flattened.append(item)

        print("Flattened list:", flattened)

except (ValueError, SyntaxError):
    print("Invalid list format.")
