#!/bin/bash
if [[ $# -ne 1 ]]; then
    echo "Usage: $0 <directory>"
    exit 1
fi

dir="$1"
mkdir -p logs

if [[ ! -d "$dir" ]]; then
    echo "Directory does not exist: $dir"
    exit 1
fi

count=0
while IFS= read -r -d '' file; do
    echo "Backup found: $file"
    ((count++))
done < <(find "$dir" -type f -name "*.bak" -print0)

if (( count == 0 )); then
    echo "No backup files found" | tee -a logs/backup_log.txt
else
    echo "Total backup files: $count"
fi
