<?php

require_once __DIR__.'/../utils/validate_creation.php';

use dao\UsersDAO;
use Database;

$data = json_decode(file_get_contents("php://input"), true);
