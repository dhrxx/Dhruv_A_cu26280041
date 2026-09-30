#!/bin/bash
mkdir -p logs
ls data /path/that/does/not/exist > logs/combined.log 2>&1
echo "Combined output/error log:"
cat logs/combined.log
