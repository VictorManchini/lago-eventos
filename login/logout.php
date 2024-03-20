<?php
session_start();
session_destroy();
header("Location: ../LagoEventos/lagoEventos.php");
?>