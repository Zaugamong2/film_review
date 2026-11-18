<section class="hero">
    <h2>Is it Fresh or Rotten?</h2>
    <p>Every film on <?=h(SITE_NAME)?> gets a score out of 100 from one of our reviewers. A score of 60 or more is Fresh. Anything lower is Rotten.</p>
    <p><a class="button" href="films.php">Read all reviews</a> <a class="button secondary" href="addfilm.php">Add a review</a></p>
</section>

<h2>Top rated right now</h2>
<div class="filmgrid">
    <?php
    $showActions = false;
    foreach ($films as $film) {
        include 'templates/filmcard.html.php';
    }
    ?>
</div>
