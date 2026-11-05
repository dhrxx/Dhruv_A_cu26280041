"""Develop a Python script that reads CSV file data and bulk-inserts rows into a MySQL database table."""

import argparse
import csv

from lab_db import connect


def main():
    parser = argparse.ArgumentParser(description="Import users from a CSV file.")
    parser.add_argument("csv_file")
    args = parser.parse_args()

    with open(args.csv_file, newline="", encoding="utf-8-sig") as source:
        reader = csv.DictReader(source)
        if not reader.fieldnames or not {"name", "email"}.issubset(reader.fieldnames):
            raise ValueError("CSV file must contain 'name' and 'email' columns.")
        users = [
            (row["name"].strip(), row["email"].strip())
            for row in reader
            if row.get("name", "").strip() and row.get("email", "").strip()
        ]

    if not users:
        print("No valid user rows to import.")
        return

    connection = connect()
    try:
        cursor = connection.cursor()
        try:
            cursor.executemany(
                "INSERT INTO users (name, email) VALUES (%s, %s)",
                users,
            )
            connection.commit()
            print("Imported", cursor.rowcount, "user records.")
        finally:
            cursor.close()
    except Exception:
        connection.rollback()
        raise
    finally:
        connection.close()


if __name__ == "__main__":
    main()
