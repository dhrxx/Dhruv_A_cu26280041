#!/bin/bash
if [[ $# -lt 1 ]]; then
    echo "Usage: $0 <integer>"
    exit 1
fi

if (( $1 % 2 == 0 )); then
    echo "$1 is even"
else
    echo "$1 is odd"
fi
