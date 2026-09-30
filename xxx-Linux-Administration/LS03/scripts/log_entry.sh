#!/bin/bash
if [[ $# -lt 1 ]]; then
    echo "Usage: $0 <message>"
    exit 1
fi

mkdir -p logs
printf '[%s] %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*" >> logs/activity.log
echo "Log entry added."
