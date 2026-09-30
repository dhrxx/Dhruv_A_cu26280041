#!/bin/bash
if [[ $# -lt 1 ]]; then
    echo "Usage: $0 <path>"
    exit 1
fi

path="$1"

if [[ -f "$path" ]]; then
    echo "$path is a regular file."
    if [[ -x "$path" ]]; then
        echo "The file is executable."
    else
        echo "The file is not executable."
    fi
elif [[ -d "$path" ]]; then
    echo "$path is a directory."
else
    echo "$path is neither a regular file nor a directory."
fi
