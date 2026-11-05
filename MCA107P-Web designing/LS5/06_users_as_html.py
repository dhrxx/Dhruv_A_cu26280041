"""Develop a Python script that retrieves all records from the `users` table and formats them into an HTML response string."""

from html import escape

from lab_db import connect


def get_users_html():
    connection = connect()
    try:
        cursor = connection.cursor()
        try:
            cursor.execute("SELECT id, name, email FROM users ORDER BY id")
            rows = cursor.fetchall()
        finally:
            cursor.close()
    finally:
        connection.close()

    table_rows = "".join(
        "<tr>"
        f"<td>{user_id}</td>"
        f"<td>{escape(name)}</td>"
        f"<td>{escape(email)}</td>"
        "</tr>"
        for user_id, name, email in rows
    )
    return (
        "<!doctype html><html lang=\"en\"><head><meta charset=\"utf-8\">"
        "<title>Users</title></head><body><h1>Users</h1>"
        "<table><thead><tr><th>ID</th><th>Name</th><th>Email</th></tr></thead>"
        f"<tbody>{table_rows}</tbody></table></body></html>"
    )


if __name__ == "__main__":
    print("Content-Type: text/html; charset=utf-8\n")
    print(get_users_html())
