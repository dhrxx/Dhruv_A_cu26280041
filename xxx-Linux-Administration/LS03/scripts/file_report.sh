#!/bin/bash
if [[ $# -ne 1 ]]; then
    echo "Usage: $0 <filename>"
    exit 1
fi

file="$1"
mkdir -p logs

if [[ ! -f "$file" ]]; then
    echo "Failure: file does not exist: $file"
    printf '[%s] FAILURE missing: %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$file" >> logs/execution_history.log
    exit 1
fi

if [[ ! -r "$file" ]]; then
    echo "Failure: file is not readable: $file"
    printf '[%s] FAILURE unreadable: %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$file" >> logs/execution_history.log
    exit 1
fi

echo "File: $file"
wc "$file"

printf '[%s] SUCCESS processed: %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$file" >> logs/execution_history.log
