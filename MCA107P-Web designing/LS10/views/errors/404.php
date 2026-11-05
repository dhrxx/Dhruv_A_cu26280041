<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
render('errors/page', ['title' => 'Page not found', 'message' => 'The requested page could not be found.']);
