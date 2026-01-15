<?php
declare(strict_types=1);

$_SESSION = [];
session_destroy();

json_ok(['ok' => true]);