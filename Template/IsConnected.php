
<?php

if (!isset($_COOKIE['logged_in']) || $_COOKIE['logged_in'] !== 'true') {
    header('Location: direction.php');
    exit();
}

ob_start();
?>

<!DOCTYPE html>
<html lang="">
<head>
    <title>Connecté</title>
</head>

<body>
tu es connecté bravo

<form action="Template/direction.php" method="post">
    <input type="hidden" name="logout" value="true">
    <button type="submit">Déconnexion</button>
</form>

</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>