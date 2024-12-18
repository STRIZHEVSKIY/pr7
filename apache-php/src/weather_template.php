<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather</title>
    <style>
        span { margin: 10px; }
        .list { display: flex; flex-direction: column; }
        .item { display: flex; flex-direction: row; cursor: pointer; text-decoration: underline; color: blue; }
        .item:hover { background-color: cadetblue; color: blueviolet; }
    </style>
</head>
<body>
    <h1>Weather</h1>
    <div class="list">
        <?php if (!empty($weatherData)): ?>
            <?php foreach ($weatherData as $data): ?>
                <?php
                    $id = htmlspecialchars($data[DatabaseConfig::ID_COLUMN]);
                    $title = htmlspecialchars($data[DatabaseConfig::TITLE_COLUMN]);
                ?>
                <div class="item" onclick="window.location.href='CityDetail.php?id=<?php echo $id; ?>'">
                    <span><?php echo $title; ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Empty</p>
        <?php endif; ?>
    </div>
</body>
</html>
