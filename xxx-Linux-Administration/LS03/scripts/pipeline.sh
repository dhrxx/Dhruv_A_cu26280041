#!/bin/bash
word="${1:-bash}"
echo "Processes matching: $word"
ps aux | grep -i -- "$word" | grep -v grep

echo
echo "Matching line count:"
ps aux | grep -i -- "$word" | grep -v grep | wc -l
