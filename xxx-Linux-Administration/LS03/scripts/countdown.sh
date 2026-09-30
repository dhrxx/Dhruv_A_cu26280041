#!/bin/bash
n=10
while (( n >= 1 )); do
    echo "$n"
    ((n--))
done
echo "Done!"
