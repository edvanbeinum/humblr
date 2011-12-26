# Humblr
### The 'so simple it could have been written by an idiot' PHP wrapper around the public Tumblr API

The source code should be reasonably well commented, but here are some examples to get you on your way

` <?php
$tumblrConsumerKey = "ESeVhsqqFQffufDFsdaZJk3Mks1zM8UiZBWUzGYVwZ8eU";
$tumblrSecretKey = "mZRgkPOEEL3htwJBZ5QbNwiY4LMWVZBV156rTRtRvYHG3TWCc5j";
$humblr = new Humblr("david.tumblr.com", $tumblrConsumerKey, $tumblrSecretKey);

/**
 * Get first 9 posts
 */

$posts = $humblr->getPosts(array('limit' => 9));
?>

<?php foreach ($posts as $post): ?>
    <div class="box col">
        <a href="<?php echo str_replace('http://www.beatsbass.com', '', $post->post_url) ?>">
            <img src="<?php echo $post->photos[0]->original_size->url; ?>" width="250" height="250"/>
        </a>
        <?php echo $post->caption ?>
        <?php if (isset($post->note_count)): ?>
        <p>
            <a href="<?php echo $post->post_url; ?>"><?php echo $post->note_count;?>
                notes</a></p>
        <?php endif; ?>
        <ul class="tags">
            <?php $tagCount = count($post->tags); ?>
            <?php foreach ($post->tags as $tag): ?>
            <li><a href="/tagged/<?php echo urlencode($tag); ?>"><?php echo $tag; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endforeach; ?>`

`<?php
/**
 * Get Blog Info
 */
$info = $humblr->getInfo();

echo $info->name;
echo strftime("%V,%G,%Y", $info->updated);
echo $info->description;
?>`

`<?php
/**
 * Get Blog Avatar
 */
??
<?php
<img src="<?php echo $humbler->getAvatar(128); ?>" width="128", height="128" />
?>`

