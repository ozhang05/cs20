<?php
$n = $_GET['n'];

function generateFibonacci($n) {
    $fib = [0, 1];
    for ($i = 2; $i < $n; $i++) {
        $fib[] = $fib[$i - 1] + $fib[$i - 2];
    }
    return $fib;
}

$fibSequence = generateFibonacci($n);

header('Content-Type: application/json');
echo json_encode(['data' => $fibSequence]);
?>