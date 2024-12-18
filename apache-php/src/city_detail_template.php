<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Detail</title>
</head>
<body>
    <h1>City Details</h1>

    <?php if (isset($city)): ?>
        <h2><?php echo htmlspecialchars($city[DatabaseConfig::TITLE_COLUMN]); ?></h2>
        <p><?php echo htmlspecialchars($city['description']); ?></p>
        <p>Температура: <?php echo htmlspecialchars($city['temperature']); ?> Градусов</p>
    <?php else: ?>
        <p>City not found.</p>
    <?php endif; ?>

    <a href="goods.php">Back to list</a>
</body>
</html>
