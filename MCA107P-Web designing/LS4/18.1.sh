#!/bin/bash
# Terminal 1: follow logs; Ctrl+C to stop.
sudo tail -f /var/log/apache2/access.log /var/log/apache2/error.log

# In a second terminal, run these tests:
# curl -I http://localhost
# curl -i http://localhost/this-page-does-not-exist
# Check access.log for requests/status codes (e.g., 200 or 404).
# A 404 may be recorded in access.log without an error.log entry.
