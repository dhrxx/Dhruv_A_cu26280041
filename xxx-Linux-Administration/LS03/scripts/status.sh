#!/bin/bash
if [[ $# -ne 1 ]]; then
    echo "Usage: $0 <directory>"
    exit 1
fi

if [[ -d "$1" ]]; then
    echo "Directory exists: $1"
    exit 0
else
    echo "Directory does not exist: $1"
    exit 1
fi
