<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper" style="display: flex; flex-basis: min-content;">
    <form action="class/login.php" method="post">
    <label>Usuari:</label>    
    <input type="text" name="user">
    <label>Constrasenya:</label>  
    <input type="password" name="password">
    <button type="submit">Log in</button>
    </form>
</div>

</body>
</html>