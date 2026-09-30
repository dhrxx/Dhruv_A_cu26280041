#!/bin/bash
if [[ ! -f data/names.txt ]]; then
    echo "Missing data/names.txt"
    exit 1
fi

sort -u < data/names.txt
