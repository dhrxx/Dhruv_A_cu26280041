"""Write a Python script that verifies and sanitizes incoming web form inputs before executing SQL write operations."""

import argparse
import re

from lab_db import connect


EMAIL_PATTERN = re.compile(r"^[^@\s]+@[^@\s]+\.[^@\s]+$")


def validate_user(name, email):
    name = name.strip()
    email = email.strip()
    if not name or len(name) > 100 or any(ord(char) < 32 for char in name):
        raise ValueError("Name must contain 1-100 printable characters.")
    if len(email) > 254 or not EMAIL_PATTERN.fullmatch(email):
        raise ValueError("Enter a valid email address (maximum 254 characters).")
    return name, email


def main():
    parser = argparse.ArgumentParser(description="Validate and insert a user.")
    parser.add_argument("name")
    parser.add_argument("email")
    args = parser.parse_args()
    name, email = validate_user(args.name, args.email)

    connection = connect()
    try:
        cursor = connection.cursor()
        try:
            cursor.execute(
                "INSERT INTO users (name, email) VALUES (%s, %s)",
                (name, email),
            )
            connection.commit()
            print("Added user with ID", cursor.lastrowid)
        finally:
            cursor.close()
    except Exception:
        connection.rollback()
        raise
    finally:
        connection.close()


if __name__ == "__main__":
    main()
