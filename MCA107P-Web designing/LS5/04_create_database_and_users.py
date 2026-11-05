"""Create a Python script that connects to MySQL and creates a database named `python_web_db` with a table `users`."""

from lab_db import connect


def main():
    connection = connect(database=False)
    try:
        cursor = connection.cursor()
        try:
            cursor.execute("CREATE DATABASE IF NOT EXISTS `python_web_db`")
            cursor.execute("USE `python_web_db`")
            cursor.execute(
                """
                CREATE TABLE IF NOT EXISTS `users` (
                    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(100) NOT NULL,
                    `email` VARCHAR(254) NOT NULL,
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                )
                """
            )
            connection.commit()
            print("Database python_web_db and table users are ready.")
        finally:
            cursor.close()
    except Exception:
        connection.rollback()
        raise
    finally:
        connection.close()


if __name__ == "__main__":
    main()
