"""Implement a Python script to update user profile information in MySQL based on a given user ID."""

import argparse

from lab_db import connect


def main():
    parser = argparse.ArgumentParser(description="Update a user's name and email.")
    parser.add_argument("user_id", type=int)
    parser.add_argument("name")
    parser.add_argument("email")
    args = parser.parse_args()
    if args.user_id <= 0:
        parser.error("user_id must be a positive integer")

    connection = connect()
    try:
        cursor = connection.cursor()
        try:
            cursor.execute(
                "UPDATE users SET name = %s, email = %s WHERE id = %s",
                (args.name.strip(), args.email.strip(), args.user_id),
            )
            connection.commit()
            if cursor.rowcount:
                print("Updated user", args.user_id)
            else:
                print("No user found with ID", args.user_id)
        finally:
            cursor.close()
    except Exception:
        connection.rollback()
        raise
    finally:
        connection.close()


if __name__ == "__main__":
    main()
