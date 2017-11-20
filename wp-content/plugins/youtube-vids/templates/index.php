<div class='wrapper'>
    <h2><?= self::PAGE_TITLE; ?></h2>
    <br />
    <p>Is Access Token Valid?
        <?php if($Driver->checkAccessToken()): ?>
            <span style='color: rgb(0,250,0); font-weight: bold; font-size: 20px;'>Yes</span>
        <?php else: ?>
            <span style='color: rgb(250,0,0); font-weight: bold; font-size: 20px;'>No</span>
        <?php endif; ?>
    </p>
    <a href="<?= $Driver->createAuthUrl(); ?>"><input type="submit" name="submit" id="submit" class="button button-primary" value="Authenticate App"></a>
</div>
