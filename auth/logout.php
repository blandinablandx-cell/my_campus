<?php
session_start();
// Futa session zote na umrudishe mtumiaji kwenye ukurasa wa login
session_unset();
session_destroy();

header("Location: login.php");
exit();
?>