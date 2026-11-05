"""Write a Python script utilizing SQLAlchemy ORM to map Python classes to MySQL database tables."""

import os

try:
    from sqlalchemy import Integer, String, create_engine
    from sqlalchemy.engine import URL
    from sqlalchemy.orm import DeclarativeBase, Mapped, mapped_column, sessionmaker
except ImportError as exc:
    raise RuntimeError(
        "Install SQLAlchemy and mysql-connector-python using "
        "02_install_mysql_connectors.ps1."
    ) from exc


class Base(DeclarativeBase):
    pass


class User(Base):
    __tablename__ = "users"

    id: Mapped[int] = mapped_column(Integer, primary_key=True, autoincrement=True)
    name: Mapped[str] = mapped_column(String(100), nullable=False)
    email: Mapped[str] = mapped_column(String(254), nullable=False)


def main():
    user = os.environ.get("MYSQL_USER")
    if not user:
        raise RuntimeError("Set MYSQL_USER before running this example.")
    url = URL.create(
        "mysql+mysqlconnector",
        username=user,
        password=os.environ.get("MYSQL_PASSWORD", ""),
        host=os.environ.get("MYSQL_HOST", "127.0.0.1"),
        port=int(os.environ.get("MYSQL_PORT", "3306")),
        database=os.environ.get("MYSQL_DATABASE", "python_web_db"),
    )
    engine = create_engine(url, pool_pre_ping=True)
    Base.metadata.create_all(engine)
    Session = sessionmaker(bind=engine)
    with Session() as session:
        for row in session.query(User).order_by(User.id):
            print(row.id, row.name, row.email)
    engine.dispose()


if __name__ == "__main__":
    main()
