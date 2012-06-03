<?php

include('header.php');
$effect = $_GET['id'];

echo "<h1>The $effect effect</h1>";

echo '<p>Using any of these transition effects couldn\'t be any easier, simply add <code style="background:#fff; padding: 2px;"><em>data-transition="'.$effect.'"</em></code> to your anchor link and you\'re all set.</p>';

include('footer.php');