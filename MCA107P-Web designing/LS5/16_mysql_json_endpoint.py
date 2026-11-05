"""Test Apache and Python integration by creating a dynamic web endpoint that generates JSON responses from MySQL queries."""

import json
import logging

from lab_db import connect


logger = logging.getLogger(__name__)


def application(environ, start_response):
    if environ.get("PATH_INFO") not in ("", "/"):
        body = json.dumps({"error": "Not found"}).encode("utf-8")
        start_response(
            "404 Not Found",
            [("Content-Type", "application/json; charset=utf-8")],
        )
        return [body]

    connection = None
    try:
        connection = connect()
        cursor = connection.cursor()
        try:
            cursor.execute("SELECT id, name, email FROM users ORDER BY id")
            users = [
                {"id": user_id, "name": name, "email": email}
                for user_id, name, email in cursor.fetchall()
            ]
        finally:
            cursor.close()
        body = json.dumps({"users": users}).encode("utf-8")
        status = "200 OK"
    except Exception:
        logger.exception("Could not build the users JSON response")
        body = json.dumps({"error": "Database request failed"}).encode("utf-8")
        status = "500 Internal Server Error"
    finally:
        if connection is not None:
            connection.close()

    start_response(
        status,
        [
            ("Content-Type", "application/json; charset=utf-8"),
            ("Content-Length", str(len(body))),
        ],
    )
    return [body]
