<?php
declare(strict_types=1);

session_unset();
session_destroy();

json_response(['ok' => true]);
