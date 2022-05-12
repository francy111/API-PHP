

<link rel="stylesheet" href="styles.css">
<img src ="./../badge.png" alt="badge" width="65vm" height="65vh" class="info">

<svg height="100%" width="100%">
    <rect x="0" y="0" fill="#434343" width="100%" height="100%"/>
    <rect y="-5%" fill="#3B62CC" width="100%" height="15%" rx="3%"/>
    <rect x="75%" fill="#3B62CC" width="100%" height="30%"/>
    <ellipse cx="75%" cy="30%" rx="25%" ry="20%" fill="#434343"/>

    <rect x="93%" y="2%" fill="#DEDEDE" width="6%" height="12%" rx="1%" ry="2%"/>

    <rect x="0%" y="88%" fill="#2b2b2b" width="100%" height="12%"/>
</svg>

<form method="POST" class="sopraSVG aboutus" action="../info.php">
    <input class="pulsanti" type="submit" value="About Us">
</form>

<p class="sopraSVG ftr">
    © 2022 MulteOnline  -  Created by Francy
</p>

<script type="text/php">
    function info(){
        header("Location: ../info.php");
    }
</script>