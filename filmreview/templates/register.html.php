<h2>Register</h2>
<p>Create an account to view and manage the film reviews.</p>

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

<form action="register.php" method="post">
    <div class="field">
        <label for="name">Your name</label>
        <input type="text" id="name" name="name" value="<?=h($values['name'])?>" maxlength="100" autocomplete="name" required>
    </div>
    <div class="field">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" value="<?=h($values['email'])?>" maxlength="150" autocomplete="email" required>
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" minlength="8" maxlength="72" autocomplete="new-password" required aria-describedby="passwordhelp">
        <p class="hint" id="passwordhelp">8 to 72 characters.</p>
    </div>
    <div class="field">
        <label for="confirm">Confirm password</label>
        <input type="password" id="confirm" name="confirm" minlength="8" maxlength="72" autocomplete="new-password" required>
    </div>
    <button type="submit" class="button">Create account</button>
</form>

<p>Already registered? <a href="login.php">Log in</a>.</p>
