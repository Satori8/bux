<?php
session_start();
if(isset($_SESSION)) session_destroy();
?>

<script type="text/javascript">location.replace("/");</script>
<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>