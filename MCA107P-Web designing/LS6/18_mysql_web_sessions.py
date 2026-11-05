"""Implement user session handling in a Python web script interacting with MySQL backend storage."""

import json
import secrets
from hashlib import sha256
from http.cookies import SimpleCookie

from lab_db import connect


def application(environ, start_response):
    connection = connect()
    set_cookie = None
    try:
        cursor = connection.cursor()
        try:
            cursor.execute(
                """
                CREATE TABLE IF NOT EXISTS web_sessions (
                    session_id CHAR(64) PRIMARY KEY,
                    payload LONGTEXT NOT NULL,
                    expires_at TIMESTAMP NOT NULL,
                    INDEX (expires_at)
                )
                """
            )
            cookie = SimpleCookie()
            cookie.load(environ.get("HTTP_COOKIE", ""))
            token = cookie["session_id"].value if "session_id" in cookie else ""
            session_id = sha256(token.encode("utf-8")).hexdigest() if token else ""
            cursor.execute(
                "SELECT payload FROM web_sessions "
                "WHERE session_id = %s AND expires_at > CURRENT_TIMESTAMP",
                (session_id,),
            )
            row = cursor.fetchone()
            if row:
                session = json.loads(row[0])
                session["visits"] = int(session.get("visits", 0)) + 1
            else:
                token = secrets.token_urlsafe(32)
                session_id = sha256(token.encode("utf-8")).hexdigest()
                session = {"visits": 1}
                set_cookie = f"session_id={token}; Path=/; HttpOnly; SameSite=Lax"
                if environ.get("HTTPS", "").lower() in ("on", "1", "true"):
                    set_cookie += "; Secure"

            cursor.execute(
                """
                INSERT INTO web_sessions (session_id, payload, expires_at)
                VALUES (%s, %s, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 7 DAY))
                ON DUPLICATE KEY UPDATE
                    payload = VALUES(payload),
                    expires_at = VALUES(expires_at)
                """,
                (session_id, json.dumps(session)),
            )
            connection.commit()
        finally:
            cursor.close()
    except Exception:
        connection.rollback()
        raise
    finally:
        connection.close()

    body = f"Session visit number: {session['visits']}\n".encode("utf-8")
    headers = [
        ("Content-Type", "text/plain; charset=utf-8"),
        ("Content-Length", str(len(body))),
    ]
    if set_cookie:
        headers.append(("Set-Cookie", set_cookie))
    start_response("200 OK", headers)
    return [body]
