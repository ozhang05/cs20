<?php

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require_once('config.php');

// Get form data
$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$instructions = $_GET['instructions'];
$pickup_time = $_GET['pickup_time'] ?: 'Now';  // Default pickup time if not specified

// Get quantities from form submission
$burger_quantity = isset($_GET['quantity_1']) ? $_GET['quantity_1'] : 0;
$pizza_quantity = isset($_GET['quantity_2']) ? $_GET['quantity_2'] : 0;
$salad_quantity = isset($_GET['quantity_3']) ? $_GET['quantity_3'] : 0;
$pasta_quantity = isset($_GET['quantity_4']) ? $_GET['quantity_4'] : 0;
$milkshake_quantity = isset($_GET['quantity_5']) ? $_GET['quantity_5'] : 0;
$fries_quantity = isset($_GET['quantity_6']) ? $_GET['quantity_6'] : 0;

// Query to get menu prices
$query = "SELECT id, name, price FROM menu WHERE id IN (1, 2, 3, 4, 5, 6)";
$result = $mysqli->query($query);

$subtotal = 0;
$menu_items = [];
$menu_prices = [];

while ($row = $result->fetch_assoc()) {
    $menu_items[$row['id']] = $row['name'];
    $menu_prices[$row['id']] = $row['price'];
}

// Calculate subtotal for each item
$order_details = [];
$order_details['burger'] = $burger_quantity * $menu_prices[1];
$order_details['pizza'] = $pizza_quantity * $menu_prices[2];
$order_details['salad'] = $salad_quantity * $menu_prices[3];
$order_details['pasta'] = $pasta_quantity * $menu_prices[4];
$order_details['milkshake'] = $milkshake_quantity * $menu_prices[5];
$order_details['fries'] = $fries_quantity * $menu_prices[6];

$subtotal = $order_details['burger'] + $order_details['pizza'] + $order_details['salad'] + $order_details['pasta'] + $order_details['milkshake'] + $order_details['fries'];

// Calculate tax and total
$tax_rate = 0.0625;
$tax = $subtotal * $tax_rate;
$total = $subtotal + $tax;

$order_date = date("Y-m-d H:i:s");

// Calculate the pickup time (20 minutes after the order time)
$pickup_time = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($order_date)));


// Insert order into database
$query = "INSERT INTO orders 
            (first_name, last_name, burger_quantity, pizza_quantity, salad_quantity, pasta_quantity, milkshake_quantity, fries_quantity, instructions, pickup_time, order_date) 
          VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($query);
$order_date = date("Y-m-d H:i:s");
$stmt->bind_param("ssiiiiiiiss", $first_name, $last_name, $burger_quantity, $pizza_quantity, $salad_quantity, $pasta_quantity, $milkshake_quantity, $fries_quantity, $instructions, $pickup_time, $order_date);

if ($stmt->execute()) {
    // Show the order details
    echo "<h1>Order Summary</h1>";
    echo "<p><strong>Name:</strong> $first_name $last_name</p>";
    echo "<p><strong>Special Instructions:</strong> $instructions</p>";
    echo "<p><strong>Pickup Time:</strong> $pickup_time</p>";

    echo "<h2>Items Ordered:</h2>";
    foreach ($menu_items as $id => $name) {
        // Get the quantity and calculate the total price for each item
        $quantity = ${"quantity_$id"};
        if ($quantity > 0) {
            $item_price = $menu_prices[$id];
            $item_total = $quantity * $item_price;
            echo "<p><strong>$name</strong><br>";
            echo "Quantity: $quantity<br>";
            echo "Price: $" . number_format($item_price, 2) . "<br>";
            echo "Total for item: $" . number_format($item_total, 2) . "</p>";
        }
    }

    // Display subtotal, tax, and total
    echo "<h3>Summary</h3>";
    echo "<p><strong>Subtotal:</strong> $" . number_format($subtotal, 2) . "</p>";
    echo "<p><strong>Tax (6.25%):</strong> $" . number_format($tax, 2) . "</p>";
    echo "<p><strong>Total:</strong> $" . number_format($total, 2) . "</p>";
} else {
    echo "Error placing order: " . $stmt->error;
}

// Close the database connection
$mysqli->close();

?>
