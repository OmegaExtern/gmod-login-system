<?php
require_once('Constants.php');
exit('<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>gmod-login-system : POST test</title>
</head>
<body>
<form action="main.php" method="POST">
    <label for="do">
        do: <input id="do" name="do" placeholder="do" type="text" value="REGISTER" required>
    </label><br>
    <label for="community_identifier">
        Community ID: <input id="community_identifier" name="community_identifier" pattern="' . COMMUNITY_ID_REGEX . '" placeholder="Community ID" type="text" value="76561198092225548" required>
    </label><br>
    <label for="name">
        Name: <input id="name" name="name" pattern="' . NAME_REGEX . '" placeholder="Name" type="text" value="CaptainPRICE" required>
    </label><br>
    <input id="submit" type="submit">
</form>
</body>
</html>');