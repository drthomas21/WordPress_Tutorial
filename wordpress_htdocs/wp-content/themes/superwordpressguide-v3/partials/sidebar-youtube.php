<div class="youtube-vids">
    <?php if(function_exists('list_popular_videos')): ?>
        <h2 class='text-center'>Hot Youtube Vids</h2>
        <?php foreach(list_popular_videos(0,5) as $Video): ?>
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= $Video->id; ?>?rel=0" allowfullscreen></iframe>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
