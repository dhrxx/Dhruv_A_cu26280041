"""Write a Python CGI script that reads form input data via `cgi` module and inserts the submitted data into MySQL."""

import os
import sys
from urllib.parse import parse_qs

from lab_db import connect

try:
    import cgi
except ImportError:
    cgi = None


def read_form():
    if cgi is None:
        content_type = os.environ.get("CONTENT_TYPE", "")
        if content_type.startswith("application/x-www-form-urlencoded"):
            length = int(os.environ.get("CONTENT_LENGTH", "0"))
            raw_body = sys.stdin.read(length)
            fields = parse_qs(raw_body, keep_blank_values=True)
            return {key: values[0] for key, values in fields.items()}
        return {}

    form = cgi.FieldStorage(
        fp=sys.stdin,
        environ=os.environ,
        keep_blank_values=True,
    )
    return {key: form.getfirst(key, "") for key in form.keys()}


def main():
    form = read_form()
    name = form.get("name", "").strip()
    email = form.get("email", "").strip()
    if not name or not email:
        status = "400 Bad Request"
        message = "Both name and email are required."
    else:
        connection = connect()
        try:
            cursor = connection.cursor()
            try:
                cursor.execute(
                    "INSERT INTO users (name, email) VALUES (%s, %s)",
                    (name, email),
                )
                connection.commit()
                status = "201 Created"
                message = "User added successfully."
            finally:
                cursor.close()
        except Exception:
            connection.rollback()
            raise
        finally:
            connection.close()

    body = f"{message}\n"
    print(f"Status: {status}")
    print("Content-Type: text/plain; charset=utf-8")
    print(f"Content-Length: {len(body.encode('utf-8'))}")
    print()
    print(body, end="")


if __name__ == "__main__":
    main()
