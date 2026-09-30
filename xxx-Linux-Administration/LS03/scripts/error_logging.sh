#!/bin/bash
mkdir -p logs
ls /path/that/does/not/exist 2> logs/errors.log
echo "Error log:"
cat logs/errors.log
