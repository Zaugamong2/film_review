<?php $label = ratingLabel($film['score']); ?>
<article class="filmcard">
    <div class="poster">
        <?php if ($film['poster']): ?>
            <img src="uploads/<?=h($film['poster'])?>" alt="Poster for <?=h($film['title'])?>" loading="lazy">
        <?php else: ?>
            <div class="noposter" role="img" aria-label="No poster available for <?=h($film['title'])?>">No poster</div>
        <?php endif; ?>
    </div>
    <div class="filmbody">
        <p class="score <?=strtolower($label)?>">
            <span class="scorenum"><?=(int)$film['score']?>%</span>
            <span class="scorelabel"><?=$label?></span>
        </p>
        <h3><?=h($film['title'])?> <span class="year">(<?=(int)$film['release_year']?>)</span></h3>
        <p class="meta"><?=h($film['genrename'])?>. Reviewed by <?=h($film['reviewername'])?> on <?=formatDate($film['reviewdate'])?></p>
        <blockquote><p><?=nl2br(h($film['reviewtext']))?></p></blockquote>
        <?php if (!empty($showActions)): ?>
            <div class="actions">
                <a class="button secondary" href="editfilm.php?id=<?=(int)$film['id']?>" aria-label="Edit review of <?=h($film['title'])?>">Edit</a>
                <form action="deletefilm.php" method="post" onsubmit="return confirm('Delete this review? This cannot be undone.');">
                    <input type="hidden" name="id" value="<?=(int)$film['id']?>">
                    <button type="submit" class="danger" aria-label="Delete review of <?=h($film['title'])?>">Delete</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</article>
