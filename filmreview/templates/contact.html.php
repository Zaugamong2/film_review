<h2>Contact the administrator</h2>
<p>Questions about a review or the site? Send a message and the administrator will reply by email.</p>

<?php if ($sent === 1): ?>
    <div class="notice" role="status"><p>Thank you. Your message has been sent to the administrator.</p></div>
<?php elseif ($sent === 2): ?>
    <div class="notice" role="status"><p>Thank you. Your message was saved for the administrator.</p></div>
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

<form action="contact.php" method="post">
    <div class="field">
        <label for="name">Your name</label>
        <input type="text" id="name" name="name" value="<?=h($values['name'])?>" maxlength="100" autocomplete="name" required>
    </div>
    <div class="field">
        <label for="email">Your email address</label>
        <input type="email" id="email" name="email" value="<?=h($values['email'])?>" maxlength="150" autocomplete="email" required>
    </div>
    <div class="field">
        <label for="message">Message</label>
        <textarea id="message" name="message" rows="6" minlength="10" maxlength="2000" required><?=h($values['message'])?></textarea>
    </div>
    <button type="submit" class="button">Send message</button>
</form>
