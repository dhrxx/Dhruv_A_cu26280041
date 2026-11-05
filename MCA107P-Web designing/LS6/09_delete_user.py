"""Write a Python script to delete a record from the MySQL database and handle database exceptions using `try-except` blocks."""

import argparse

from lab_db import connect


def main():
    parser = argparse.ArgumentParser(description="Delete one user by ID.")
    parser.add_argument("user_id", type=int)
    args = parser.parse_args()
    if args.user_id <= 0:
        parser.error("user_id must be a positive integer")

    connection = None
    try:
        connection = connect()
        cursor = connection.cursor()
        try:
            cursor.execute("DELETE FROM users WHERE id = %s", (args.user_id,))
            connection.commit()
            if cursor.rowcount:
                print("Deleted user", args.user_id)
            else:
                print("No user found with ID", args.user_id)
        finally:
            cursor.close()
    except Exception as exc:
        if connection is not None:
            connection.rollback()
        print(f"Database operation failed: {exc}")
        raise
    finally:
        if connection is not None:
            connection.close()


if __name__ == "__main__":
    main()
