<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';
render('errors/page', ['title' => 'Server error', 'message' => 'The request could not be completed. Please try again later.']);
