<?php
// $errors, $old fournis par le controller
?>

<h1>Add a game</h1>

<form method="POST" action="/add" class="form">

    <div class="field">
        <label for="title">Title</label>
        <input id="title" type="text" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>">
        <?php if (!empty($errors['title'])): ?><p class="error"><?= htmlspecialchars($errors['title']) ?></p><?php endif; ?>
    </div>

    <div class="field">
        <label for="platform">Platform</label>
        <input id="platform" type="text" name="platform" value="<?= htmlspecialchars($old['platform'] ?? '') ?>">
        <?php if (!empty($errors['platform'])): ?><p class="error"><?= htmlspecialchars($errors['platform']) ?></p><?php endif; ?>
    </div>

    <div class="field">
        <label for="genre">Genre</label>
        <input id="genre" type="text" name="genre" value="<?= htmlspecialchars($old['genre'] ?? '') ?>">
        <?php if (!empty($errors['genre'])): ?><p class="error"><?= htmlspecialchars($errors['genre']) ?></p><?php endif; ?>
    </div>

    <div class="field two-cols">
        <div>
            <label for="releaseYear">Release year</label>
            <input id="releaseYear" type="number" name="releaseYear" value="<?= htmlspecialchars((string)($old['releaseYear'] ?? '')) ?>">
            <?php if (!empty($errors['releaseYear'])): ?><p class="error"><?= htmlspecialchars($errors['releaseYear']) ?></p><?php endif; ?>
        </div>

        <div>
            <label for="rating">Rating (0–10)</label>
            <input id="rating" type="number" min="0" max="10" name="rating" value="<?= htmlspecialchars((string)($old['rating'] ?? '')) ?>">
            <?php if (!empty($errors['rating'])): ?><p class="error"><?= htmlspecialchars($errors['rating']) ?></p><?php endif; ?>
        </div>
    </div>

    <div class="field">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
        <?php if (!empty($errors['description'])): ?><p class="error"><?= htmlspecialchars($errors['description']) ?></p><?php endif; ?>
    </div>

    <div class="field">
        <label for="notes">Notes (optional)</label>
        <textarea id="notes" name="notes" rows="3"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
    </div>

    <button type="submit" class="btn">Add</button>
</form>
