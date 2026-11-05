# Set up a local test environment to host and run the client website smoothly before presentation.
$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location -LiteralPath $root
$python = Get-Command python -ErrorAction Stop
Write-Output 'Serving the lab website at http://127.0.0.1:8000/02_semantic_structure.html'
Write-Output 'Press Ctrl+C in this terminal to stop the local server.'
& $python.Source -m http.server 8000 --bind 127.0.0.1
