"""Write a Python script to log database error messages and access timestamps into a dedicated server log file."""

import logging
import os
from pathlib import Path

from lab_db import connect


LOG_FILE = Path(os.environ.get(
    "MYSQL_ERROR_LOG",
    str(Path(__file__).with_name("mysql_errors.log")),
))
logging.basicConfig(
    filename=LOG_FILE,
    level=logging.INFO,
    format="%(asctime)s %(levelname)s %(message)s",
)
logger = logging.getLogger("lab_sheet_5.database")


def main():
    logger.info("Database access started")
    connection = None
    try:
        connection = connect()
        cursor = connection.cursor()
        try:
            cursor.execute("SELECT COUNT(*) FROM users")
            print("User count:", cursor.fetchone()[0])
            logger.info("Database access completed successfully")
        finally:
            cursor.close()
    except Exception:
        logger.exception("Database access failed")
        raise
    finally:
        if connection is not None:
            connection.close()


if __name__ == "__main__":
    main()
