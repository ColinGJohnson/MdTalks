<!DOCTYPE html>
<html>
<head>
<title>Login</title>
</head>
<body>

<?php

$users = fopen("users.json", "r");
if ($users) {
    while (($line = fgets($users)) !== false) {
        var_dump(json_decode($line, true));
    }

    fclose($users);
}

?>


</body>
</html>