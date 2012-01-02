# Humblr
### The 'so simple it could have been written by an idiot' PHP wrapper around the public Tumblr API

The source code should be reasonably well commented, but here are some examples to get you on your way.
See the <humblr.edvanbenihm.com> for a more extensive version of this doc.

Create an instance of Hublr

    $baseHostname = "david.tumblr.com";
    $tumblrConsumerKey = "ESeVhsqqFQffufDFsdaZJk3Mks1zM8UiZBWUzGYVwZ8eU";
    $humblr = new Humblr($baseHostname, $tumblrConsumerKey);

Get the first 9 posts

    $posts = $humblr->getPosts(array('limit' => 9));

which will give you an array of objects with some of the (but not limited to) following attributes

    $post->post_url;
    $post->photos;
    $post->caption;
    $post->note_count;
    $post->tags;

Get Blog Info

    $info = $humblr->getInfo();

    echo $info->name;
    echo strftime("%V,%G,%Y", $info->updated);
    echo $info->description;

Get Blog Avatar

    <img src="<?php echo $humbler->getAvatar(128); ?>" width="128", height="128" />

