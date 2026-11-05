"""Write a Python script that connects to MySQL, performs a parameterized query to prevent SQL injection, and returns filtered results."""

import argparse

from lab_db import connect


def main():
    parser = argparse.ArgumentParser(description="Find users by name.")
    parser.add_argument("name", help="Text to search for in user names")
    args = parser.parse_args()

    connection = connect()
    try:
        cursor = connection.cursor()
        try:
            cursor.execute(
                "SELECT id, name, email FROM users WHERE name LIKE %s ORDER BY id",
                (f"%{args.name}%",),
            )
            for user_id, name, email in cursor.fetchall():
                print(f"{user_id}\t{name}\t{email}")
        finally:
            cursor.close()
    finally:
        connection.close()


if __name__ == "__main__":
    main()
