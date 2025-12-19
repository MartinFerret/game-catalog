<?php
$game ??= [];
$id ??= 0;
$success ??= '';
?>

<?php if (!empty($success)): ?>
    <div class="alert alert--success">
        <?= $success ?>
    </div>
<?php endif; ?>


<?php if (!$game): ?>
    <h1>Le jeu demandé n'est pas trouvé.</h1>
<?php else: ?>
    <h1><?= $game['title'] ?></h1>
    <h1><?= $game['platform'] ?></h1>
<?php endif; ?>
