"""Implement connection pooling in Python for efficient database interaction under high Apache server concurrency."""

import os

try:
    from mysql.connector.pooling import MySQLConnectionPool
except ImportError as exc:
    raise RuntimeError(
        "Install mysql-connector-python using 02_install_mysql_connectors.ps1."
    ) from exc


pool = MySQLConnectionPool(
    pool_name="web_app_pool",
    pool_size=max(1, min(int(os.environ.get("MYSQL_POOL_SIZE", "5")), 32)),
    host=os.environ.get("MYSQL_HOST", "127.0.0.1"),
    port=int(os.environ.get("MYSQL_PORT", "3306")),
    user=os.environ.get("MYSQL_USER", ""),
    password=os.environ.get("MYSQL_PASSWORD", ""),
    database=os.environ.get("MYSQL_DATABASE", "python_web_db"),
)


def get_user_count():
    """Borrow a pooled connection for one short request and return it to the pool."""
    connection = pool.get_connection()
    try:
        cursor = connection.cursor()
        try:
            cursor.execute("SELECT COUNT(*) FROM users")
            return cursor.fetchone()[0]
        finally:
            cursor.close()
    finally:
        connection.close()


if __name__ == "__main__":
    print("User count:", get_user_count())
