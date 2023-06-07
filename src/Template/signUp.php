<?php ob_start(); ?>



<head>
    <title>Inscription</title>
    <link href="../Style/PoPUpOTP.css" rel="stylesheet">
    <script src="../Script/PopUpOTP.js"></script>
    <script>
        window.onload = function() {
            let otp = "<?php echo $_SESSION['otp']; ?>";
            if (otp) {
                showOTP(otp);
            }
        }
    </script>
</head>


<!-- <form action="../Controller/SignUp.php" method="post">-->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

    echo file_get_contents(../.);
    <label>Email:<input type="text" name="email"></label><br>
    <label>Mot de passe:<input type="password" name="password"></label><br>
    <label>Confirmer le mot de passe:<input type="password" name="password2"></label><br>
    <input type="submit" value="Inscription">
    <a href="">J'ai déjà un compte</a>
</form>

<?php
$content = ob_get_clean();
?>