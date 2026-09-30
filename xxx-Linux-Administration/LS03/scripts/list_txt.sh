#!/bin/bash
shopt -s nullglob
files=(data/*.txt)

if (( ${#files[@]} == 0 )); then
    echo "No .txt files found in data/"
    exit 0
fi

for file in "${files[@]}"; do
    echo "Text file found: $file"
done
