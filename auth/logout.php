<?php
// filepath: d:\agstation\auth\logout.php
require_once '../includes/session.php';

session_unset();
session_destroy();

header('Location: /frontend/index.php');
exit;
?>
