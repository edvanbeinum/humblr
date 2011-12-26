<?php
include_once __DIR__ . "/../../src/Humblr.php";
/**
 *
 * @author Ed van Beinum <e@edvanbeinum.com>
 * @version $Id$
 * @copyright Ed van Beinum Dec 2011
 * @package Humblr
 */
class HumblrIntegrationTest extends PHPUnit_Framework_TestCase
{

    protected $_humblr;

    public function setUp()
    {
        $this->_humblr = new Humblr('david.tumblr.com', 'ESeVhsqqFQffujBLEwfzjYeaZJk3Mks1zM8UiZBWUzGYVwZ8eU');
    }

    public function tearDown()
    {
        unset($this->_humblr);
    }

    public function test_getPosts_returns_object()
    {
        $posts = $this->_humblr->getPosts();
        $this->assertInternalType('array', $posts);
    }

    public function test_getPosts_returns_limited_posts()
    {
        $posts = $this->_humblr->getPosts(array('limit' => '2'));
        $this->assertSame(2, count($posts));
    }

    public function test_getPosts_returns_text_post_type()
    {
        $textPosts = $this->_humblr->getPosts(array('type' => 'text'));
        foreach ($textPosts as $post) {
            $this->assertSame('text', $post->type, 'Not all posts are of type text');
        }
    }

    public function test_getPosts_returns_photo_post_type()
        {
            $textPosts = $this->_humblr->getPosts(array('type' => 'photo'));
            foreach ($textPosts as $post) {
                $this->assertSame('photo', $post->type, 'Not all posts are of type photo');
            }
        }

    public function test_getAvatar_returns_image()
    {
        $this->markTestSkipped('Tumblr API returns an image resource which GD isn\'t able to retrieve data from it can only output to file to screen.');
    }

    public function test_getInfo_returns_blog_info()
    {
        $result = $this->_humblr->getInfo();
        $this->assertObjectHasAttribute('title', $result);
        $this->assertObjectHasAttribute('posts', $result);
        $this->assertObjectHasAttribute('name', $result);
    }
}