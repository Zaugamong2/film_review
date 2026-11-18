<h2><?=h($heading)?></h2>

<?php if (!empty($errors)): ?>
    <div class="errors" role="alert">
        <p>Fix these problems and try again:</p>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?=h($error)?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?=(int)$film['id']?>">
    <?php endif; ?>

    <div class="field">
        <label for="title">Film title</label>
        <input type="text" id="title" name="title" value="<?=h($film['title'])?>" maxlength="150" required>
    </div>

    <div class="field">
        <label for="release_year">Release year</label>
        <input type="number" id="release_year" name="release_year" value="<?=h($film['release_year'])?>" min="1888" max="<?=date('Y') + 2?>" required>
    </div>

    <div class="field">
        <label for="genreid">Genre</label>
        <select id="genreid" name="genreid" required>
            <option value="">Choose a genre</option>
            <?php foreach ($genres as $genre): ?>
                <option value="<?=(int)$genre['id']?>"<?= (string)$film['genreid'] === (string)$genre['id'] ? ' selected' : '' ?>><?=h($genre['name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label for="reviewerid">Reviewer</label>
        <select id="reviewerid" name="reviewerid" required>
            <option value="">Choose a reviewer</option>
            <?php foreach ($reviewers as $reviewer): ?>
                <option value="<?=(int)$reviewer['id']?>"<?= (string)$film['reviewerid'] === (string)$reviewer['id'] ? ' selected' : '' ?>><?=h($reviewer['name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label for="score">Score (0 to 100)</label>
        <input type="number" id="score" name="score" value="<?=h($film['score'])?>" min="0" max="100" required aria-describedby="scorehelp">
        <p class="hint" id="scorehelp">60 or more is Fresh. Below 60 is Rotten.</p>
    </div>

    <div class="field">
        <label for="reviewtext">Your review</label>
        <textarea id="reviewtext" name="reviewtext" rows="6" minlength="10" maxlength="2000" required><?=h($film['reviewtext'])?></textarea>
    </div>

    <div class="field">
        <label for="poster">Poster image (optional)</label>
        <input type="file" id="poster" name="poster" accept="image/jpeg,image/png,image/webp" aria-describedby="posterhelp">
        <p class="hint" id="posterhelp">JPG, PNG or WebP, up to 2 MB.</p>
        <?php if ($isEdit && $film['poster']): ?>
            <p><img class="currentposter" src="uploads/<?=h($film['poster'])?>" alt="Current poster for <?=h($film['title'])?>"></p>
            <p class="check">
                <input type="checkbox" id="removeposter" name="removeposter" value="1">
                <label for="removeposter">Remove the current poster</label>
            </p>
        <?php endif; ?>
    </div>

    <button type="submit" class="button"><?=h($submitLabel)?></button>
    <a class="button secondary" href="films.php">Cancel</a>
</form>
