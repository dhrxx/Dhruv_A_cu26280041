"""Write a Python script to insert multiple user records into the MySQL database using executemany() method."""

from lab_db import connect


def main():
    users = [
        ("Aarav Sharma", "aarav@example.com"),
        ("Mia Patel", "mia@example.com"),
        ("Noah Khan", "noah@example.com"),
    ]
    connection = connect()
    try:
        cursor = connection.cursor()
        try:
            cursor.executemany(
                "INSERT INTO users (name, email) VALUES (%s, %s)",
                users,
            )
            connection.commit()
            print("Inserted", cursor.rowcount, "user records.")
        finally:
            cursor.close()
    except Exception:
        connection.rollback()
        raise
    finally:
        connection.close()


if __name__ == "__main__":
    main()
