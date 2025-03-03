<?php
$office_hours = [
    'Monday'    => '9am - 5pm',
    'Tuesday'   => '9am - 5pm',
    'Wednesday' => '9am - 5pm',
    'Thursday'  => '9am - 5pm',
    'Friday'    => '9am - 5pm',
    'Saturday'  => '10am - 3pm',
    'Sunday'    => 'Closed'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Hours</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Office Hours</h1>

    <div class="container">
        <?php
        foreach ($office_hours as $day => $hours) {
            echo "<div>";
            echo "<h2>$day: </h2>";
            echo "<p>$hours</p>";
            echo "</div>";
        }
        ?>
    </div>

</body>
</html>