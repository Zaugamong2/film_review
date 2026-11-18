<h2>Film reviews</h2>

<form class="filter" action="films.php" method="get" role="search">
    <div class="field">
        <label for="q">Search by title</label>
        <input type="search" id="q" name="q" value="<?=h($search)?>" maxlength="150">
    </div>
    <div class="field">
        <label for="genre">Genre</label>
        <select id="genre" name="genre">
            <option value="0">All genres</option>
            <?php foreach ($genres as $genre): ?>
                <option value="<?=(int)$genre['id']?>"<?= $genreId === (int)$genre['id'] ? ' selected' : '' ?>><?=h($genre['name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="button">Filter</button>
</form>

<p role="status"><?=count($films)?> review<?= count($films) === 1 ? '' : 's' ?> found.</p>

<?php if (!$films): ?>
    <p>No reviews match. <a href="addfilm.php">Add the first one</a>.</p>
<?php else: ?>
    <div class="filmgrid">
        <?php
        $showActions = true;
        foreach ($films as $film) {
            include 'templates/filmcard.html.php';
        }
        ?>
    </div>
<?php endif; ?>
