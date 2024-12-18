<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Страница администратора</title>
    <style>
        span { margin: 10px; }
        .user-list { display: flex; flex-direction: column; }
        .user-item { display: flex; flex-direction: column; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Список пользователей</h1>
    <div class="user-list">
        <?php foreach ($users as $user): ?>
            <div class="user-item">
                <span>ID: <?= htmlspecialchars($user['ID']); ?></span>
                <span>Имя: <?= htmlspecialchars($user['name']); ?></span>
                <span>Пароль: <?= htmlspecialchars($user['password']); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
