#!/bin/bash
read -rp "Enter an integer: " n

if (( n > 0 )); then
    echo "Positive"
elif (( n < 0 )); then
    echo "Negative"
else
    echo "Zero"
fi
