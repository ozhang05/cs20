<?php

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require_once('config.php');

// Query to retrieve menu items
$query = "SELECT id, name, description, price, image FROM menu";
$result = $mysqli->query($query);

if ($result === false) {
    die("Error retrieving menu items: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
</head>
<body>
    <h1>Place Your Order.</h1>
    <form action="process_order.php" method="GET">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="instructions">Special Instructions:</label>
        <textarea id="instructions" name="instructions"></textarea><br><br>

        <h3>Order Items:</h3>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div>
                <label for="quantity_<?php echo $row['id']; ?>"><?php echo $row['name']; ?> (<?php echo $row['price']; ?>):</label>
                <input type="number" id="quantity_<?php echo $row['id']; ?>" name="quantity_<?php echo $row['id']; ?>" value="0" min="0"><br><br>
                <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" width="100"><br><br>
            </div>
        <?php endwhile; ?>

        <button type="submit">Submit Order</button>
    </form>

    <?php
    // Close the database connection
    $mysqli->close();
    ?>
</body>
</html>
