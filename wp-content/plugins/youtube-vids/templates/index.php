<div class='wrapper'>
    <h2><?php echo self::PAGE_TITLE; ?></h2>
    <p>Link your YouTube account with this App to display your vidoes onto your website.</p>
    <br />
    <p>Is Access Token Valid?
        <?php if($isValid): ?>
            <span style='color: rgb(0,250,0); font-weight: bold; font-size: 20px;'>Yes</span>
        <?php else: ?>
            <span style='color: rgb(250,0,0); font-weight: bold; font-size: 20px;'>No</span>
        <?php endif; ?>
    </p>
    <h2>Token Info:</h2>
    <ul>
        <li><strong>Has Access Token: </strong><?php echo (strlen($accessToken) > 0 ? "Yes" : "No"); ?></li>
        <li><strong>Has Refresh Token: </strong><?php echo (strlen($refreshToken) > 0 ? "Yes" : "No"); ?></li>
        <li><strong>Created: </strong><?php echo $createDate ?></li>
        <li><strong>Expires On: </strong><?php echo $expireDate ?></li>
    </ul>
    <a href="<?php echo $authUrl; ?>"><input type="submit" name="submit" id="submit" class="button button-primary" value="Authenticate App"></a>
</div>
