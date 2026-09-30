#!/bin/bash
read -rp "Enter first integer: " a
read -rp "Enter second integer: " b

echo "Sum        : $((a + b))"
echo "Difference : $((a - b))"
echo "Product    : $((a * b))"

if (( b != 0 )); then
    echo "Quotient   : $((a / b))"
else
    echo "Quotient   : undefined (division by zero)"
fi
