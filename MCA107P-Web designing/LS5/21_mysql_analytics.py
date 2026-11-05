"""Write a Python script to generate analytical summary reports (counts, averages) from MySQL data for administrative dashboards."""

from lab_db import connect


def main():
    connection = connect()
    try:
        cursor = connection.cursor()
        try:
            cursor.execute(
                """
                SELECT COUNT(*) AS user_count,
                       AVG(CHAR_LENGTH(name)) AS average_name_length,
                       AVG(CHAR_LENGTH(email)) AS average_email_length
                FROM users
                """
            )
            count, average_name_length, average_email_length = cursor.fetchone()
            print("User count:", count)
            print(
                "Average name length:",
                f"{average_name_length:.2f}" if average_name_length is not None else "0.00",
            )
            print(
                "Average email length:",
                f"{average_email_length:.2f}" if average_email_length is not None else "0.00",
            )
        finally:
            cursor.close()
    finally:
        connection.close()


if __name__ == "__main__":
    main()
