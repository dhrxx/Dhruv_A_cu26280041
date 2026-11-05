"""Write a Python script to connect to a MySQL database server and execute a query returning the database server version."""

from lab_db import connect


def main():
    connection = connect(database=False)
    try:
        cursor = connection.cursor()
        try:
            cursor.execute("SELECT VERSION()")
            print("MySQL server version:", cursor.fetchone()[0])
        finally:
            cursor.close()
    finally:
        connection.close()


if __name__ == "__main__":
    main()
