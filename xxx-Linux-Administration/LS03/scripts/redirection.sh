#!/bin/bash
mkdir -p output
ls -la data > output/files.txt
echo "First listing written with >"
cat output/files.txt

echo "Appended line using >>" >> output/files.txt
echo "Second listing:" >> output/files.txt
ls -la data >> output/files.txt

echo "Final file:"
cat output/files.txt
