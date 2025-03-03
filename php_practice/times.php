<?php
$n = $_GET['n'];

echo "<h1>Times Table for $n</h1>";
echo "<ul>";
for ($i = 1; $i <= 15; $i++) {
    $result = $i * $n;
    echo "<li>$i x $n = $result</li>";
}
echo "</ul>";
?>