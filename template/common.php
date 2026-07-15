<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $this->e($title ?? 'Microbe') ?></title>

    <meta name="description" content="<?= $this->e($description ?? '') ?>">
    <meta name="keywords" content="<?= $this->e($keywords ?? '') ?>">
    <meta name="robots" content="<?= $this->e($robots ?? 'index, follow') ?>">
</head>
<body>

    <h1><?= $this->e($title ?? 'Microbe') ?></h1>
    <p><?= $this->e($body ?? '')?></p>

</body>
</html>
