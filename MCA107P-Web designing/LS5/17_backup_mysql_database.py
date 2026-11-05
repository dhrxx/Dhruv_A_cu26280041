"""Write a Python script to backup a MySQL database schema and data into a `.sql` file programmatically."""

import math
import os
from datetime import date, datetime, time
from decimal import Decimal
from pathlib import Path

from lab_db import connect, quote_identifier


def sql_literal(value):
    if value is None:
        return "NULL"
    if isinstance(value, bool):
        return "1" if value else "0"
    if isinstance(value, int):
        return str(value)
    if isinstance(value, float) and math.isfinite(value):
        return repr(value)
    if isinstance(value, Decimal) and value.is_finite():
        return str(value)
    if isinstance(value, bytes):
        return "X'" + value.hex() + "'"
    if isinstance(value, (date, datetime, time)):
        value = value.isoformat(sep=" ") if isinstance(value, datetime) else value.isoformat()
    text = str(value)
    text = (
        text.replace("\\", "\\\\")
        .replace("\0", "\\0")
        .replace("\n", "\\n")
        .replace("\r", "\\r")
        .replace("\x1a", "\\Z")
        .replace("'", "\\'")
    )
    return "'" + text + "'"


def main():
    database = os.environ.get("MYSQL_DATABASE", "python_web_db")
    output = Path(os.environ.get("MYSQL_BACKUP_FILE", f"{database}_backup.sql"))
    connection = connect()
    try:
        cursor = connection.cursor()
        try:
            cursor.execute("SHOW FULL TABLES")
            tables = [row[0] for row in cursor.fetchall() if row[1] == "BASE TABLE"]
            lines = [
                "-- MySQL schema and data backup",
                f"-- Database: {database}",
                "SET FOREIGN_KEY_CHECKS=0;",
                "",
            ]
            for table in tables:
                cursor.execute(f"SHOW CREATE TABLE {quote_identifier(table)}")
                create_statement = cursor.fetchone()[1]
                lines.extend(
                    [
                        f"DROP TABLE IF EXISTS {quote_identifier(table)};",
                        create_statement + ";",
                    ]
                )
                cursor.execute(f"SELECT * FROM {quote_identifier(table)}")
                columns = [description[0] for description in cursor.description]
                column_sql = ", ".join(quote_identifier(column) for column in columns)
                for row in cursor:
                    values = ", ".join(sql_literal(value) for value in row)
                    lines.append(
                        f"INSERT INTO {quote_identifier(table)} ({column_sql}) "
                        f"VALUES ({values});"
                    )
                lines.append("")
            lines.append("SET FOREIGN_KEY_CHECKS=1;")
            output.write_text("\n".join(lines) + "\n", encoding="utf-8")
            print(f"Backup written to {output.resolve()}")
        finally:
            cursor.close()
    finally:
        connection.close()


if __name__ == "__main__":
    main()
