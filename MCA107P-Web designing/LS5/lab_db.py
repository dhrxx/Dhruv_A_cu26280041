"""Shared MySQL connection settings for the Lab Sheet 5 examples."""

import os


def connect(database=True):
    """Connect using MYSQL_HOST, MYSQL_PORT, MYSQL_USER, MYSQL_PASSWORD and MYSQL_DATABASE."""
    try:
        import mysql.connector
    except ImportError as exc:
        raise RuntimeError(
            "MySQL Connector/Python is missing; run the command in "
            "02_install_mysql_connectors.ps1."
        ) from exc

    user = os.environ.get("MYSQL_USER")
    if not user:
        raise RuntimeError("Set MYSQL_USER before running this example.")

    settings = {
        "host": os.environ.get("MYSQL_HOST", "127.0.0.1"),
        "port": int(os.environ.get("MYSQL_PORT", "3306")),
        "user": user,
        "password": os.environ.get("MYSQL_PASSWORD", ""),
    }
    selected_database = os.environ.get("MYSQL_DATABASE", "python_web_db")
    if database is True:
        settings["database"] = selected_database
    elif isinstance(database, str):
        settings["database"] = database

    return mysql.connector.connect(**settings)


def quote_identifier(identifier):
    """Quote a MySQL identifier, escaping embedded backticks."""
    return "`" + identifier.replace("`", "``") + "`"
