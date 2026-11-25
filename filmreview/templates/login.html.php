<h2>Log in</h2>

<?php if ($needed): ?>
    <div class="notice" role="status"><p>Please log in or register to see the film reviews.</p></div>
<?php endif; ?>

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

<form action="login.php" method="post">
    <div class="field">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" value="<?=h($email)?>" maxlength="150" autocomplete="email" required>
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="current-password" required>
    </div>
    <button type="submit" class="button">Log in</button>
</form>

<p>No account yet? <a href="register.php">Register</a>.</p>
