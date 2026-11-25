<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="jokes.css">
        <title><?=h($title)?> | <?=h(SITE_NAME)?></title>
    </head>
    <body>
        <a class="skip-link" href="#content">Skip to main content</a>
        <header>
            <h1><?=h(SITE_NAME)?></h1>
            <p class="tagline">Honest film reviews, scored Fresh or Rotten</p>
        </header>
        <?php $current = basename($_SERVER['PHP_SELF']); ?>
        <nav aria-label="Main">
            <ul>
                <?php
                if (isLoggedIn()) {
                    $links = ['index.php' => 'Home', 'films.php' => 'Film reviews', 'addfilm.php' => 'Add a review', 'contact.php' => 'Contact'];
                } else {
                    $links = ['login.php' => 'Log in', 'register.php' => 'Register', 'contact.php' => 'Contact'];
                }
                foreach ($links as $file => $label): ?>
                    <li<?= $current === $file ? ' class="currentpage"' : '' ?>>
                        <a href="<?=$file?>"<?= $current === $file ? ' aria-current="page"' : '' ?>><?=$label?></a>
                    </li>
                <?php endforeach; ?>
                <?php if (isLoggedIn()): ?>
                    <li class="userinfo">
                        <span>Signed in as <?=h(currentUserName())?></span>
                        <form action="logout.php" method="post"><button type="submit" class="linklike">Log out</button></form>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <main id="content">
            <?=$output?>
        </main>
        <footer>&copy; <?=date('Y')?> <?=h(SITE_NAME)?></footer>
    </body>
</html>
