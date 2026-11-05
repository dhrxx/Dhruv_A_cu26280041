"""Configure Apache web server to execute Python CGI scripts or WSGI applications (mod_wsgi) and verify with a 'Hello World' Python script."""

def application(environ, start_response):
    body = b"Hello World from Python WSGI!\n"
    start_response(
        "200 OK",
        [
            ("Content-Type", "text/plain; charset=utf-8"),
            ("Content-Length", str(len(body))),
        ],
    )
    return [body]


if __name__ == "__main__":
    print("This file is a WSGI application. Configure Apache with mod_wsgi:")
    print('WSGIScriptAlias /hello "C:/path/to/01_apache_wsgi_hello.py"')
    print('<Directory "C:/path/to">')
    print("    Require all granted")
    print("</Directory>")
    print("Then restart Apache and open http://localhost/hello.")
