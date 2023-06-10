<?php

if (!isset($_COOKIE['cookies_enabled'])) {
    echo "Les cookies sont désactivés dans votre navigateur. Veuillez les activer pour utiliser pleinement notre application.";
    echo "Voici comment activer les cookies : [instructions d'activation des cookies]";
    setcookie('cookies_enabled', 'true', time() + 86400); // Enregistre un cookie indiquant que l'utilisateur a vu le message
}

?>