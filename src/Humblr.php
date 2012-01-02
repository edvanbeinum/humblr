<?php
/**
 *
 * @author Ed van Beinum <e@edvanbeinum.com>
 * @version $Id$
 * @copyright Ed van Beinum Dec 2011
 * @package Humblr
 */


/**
 * A simple class to call the public API methods on Tumblr's API. You can retrieve the following types:
 * - public posts
 * - avatar image
 * - blog info
 * You will need an API key from Tumblr in order to make requests against Tumblr's API
 *
 * @package Humblr
 * @author Ed van Beinum <e@edvanbeinum.com>
 */
class Humblr
{

    /**
     * Tumblr's API URI
     * Typically this will be "http://api.tumblr.com/v2/blog/"
     *
     * @var string
     */
    protected $_tumblrHostname;

    /**
     * The Tumblr Blogs URL; XXX.tumblr.com is usual unless you are using a CNAME
     * in which case the domain name will work as well
     *
     * @var string
     */
    protected $_baseHostname;

    /**
     * Tumblr API Consumer Key
     *
     * @var string
     */
    protected $_tumblrConsumerKey;

    /**
     * Tumblr Secret Key
     * Not actually needed for performing public API requests but included here for the sake of completeness
     *
     * @var string
     */
    protected $_tumblrSecretKey;

    /**
     * Constructor ahoy!
     * Usage would look something like this:
     *
     * <code>
     * $tumblrConsumerKey = "ESeVhsqqFQffujBLEwfzjYeaZJk3MFGDFG5gDg";
     * $tumblrSecretKey = "mZRgkPOEEL3htwJBZ5QbNwiY4LMWVZBV1dfgd00gdfg45D";
     * $tumblr = new Tumblr("yoursite.tumblr.com", $tumblrConsumerKey, $tumblrSecretKey);
     * </code>
     *
     * @param string $baseHostname
     * @param string $consumerKey
     * @param string $secretKey
     */
    public function __construct($baseHostname, $consumerKey, $secretKey = NULL)
    {
        $this->_baseHostname = $baseHostname;
        $this->_tumblrConsumerKey = $consumerKey;
        $this->_tumblrSecretKey = $secretKey;
        $this->_tumblrHostname = "http://api.tumblr.com/v2/blog/";
    }

    /**
     * Gets Blog posts from Tumblr. Extra qury string parameters can be specified by passing in an array.
     * So to limit the number of posts returned: array('limit' => 9) will be transformed into &limit=9
     * For a list of all possible parameters, see @link http://www.tumblr.com/docs/en/api/v2#posts
     *
     * If the paramters used are not recognised by Tumblr, then humblr emits and error
     * "failed to open stream: HTTP request failed! HTTP/1.1 404 Not Found" which isn;t great. Improved error handling
     * is the next thing on the todo list!
     *
     * @param array $params
     * @return object
     */
    public function getPosts($params = array())
    {
        $requestUrl = $this->_getApiUrl("posts") . $this->_getQueryString($params);
        $response = json_decode($this->_getResponse($requestUrl));
        return $response->response->posts;
    }

    /**
     * Returns an image resource of the blog's avatar, default size is 64 pixels square.
     * What's an image resource? Well, it's not a URL or path to an image, it is the actual binary data of the image itself
     * So you can use a call to this function wherever you would put an image. e.g.
     * <code>
     * <img src="<?php echo $humbler->getAvatar(); ?>" width="64", height="64" />
     * </code>
     *
     * Or you can also specify the dimensions by passing in an integer. Either: 16, 24, 30, 40, 48, 64, 96, 128, 512.
     * (all Tumblr Avatars are square)
     * <code>
     * <img src="<?php echo $humbler->getAvatar(128); ?>" width="128", height="128" />
     * </code>
     *
     * @param int|null $size
     * @return string
     */
    public function getAvatar($size = NULL)
    {
        $requestUrl = $this->_getApiUrl("avatar") . "/" . $size;
        return $this->_getResponse($requestUrl);
    }

    /**
     * Return object containing a blog's general information.
     * Things like title, number of posts, name, last updated and description
     * @link http://www.tumblr.com/docs/en/api/v2#blog-info
     *
     * @return stdClass
     */
    public function getInfo()
    {
        $requestUrl = $this->_getApiUrl("info");
        $response = json_decode($this->_getResponse($requestUrl));
        return $response->response->blog;
    }

    /**
     * Get the fully formed API URL complete with key and blog hostname
     *
     * @param string $contentType Either 'posts', 'avatar' or 'info' see: http://www.tumblr.com/docs/en/api/v2
     * @return string
     */
    protected function _getApiUrl($contentType)
    {
        return $this->_tumblrHostname . $this->_baseHostname . "/" . $contentType . "?api_key=" . $this->_tumblrConsumerKey;
    }

    /**
     * Transform an array of key values into a query string
     *
     * @param array $params
     * @return string
     */
    protected function _getQueryString(array $params)
    {
        $paramQueryString = http_build_query($params);

        // prepend an ampersand because this will be appended to an existing query string in Humblr::_getApiUrl()
        return "&" . $paramQueryString;
    }

    /**
     * Sends a request to the Tumblr API. It tries to use file_get_contents() first, then Curl and then will throw an
     * exception if neither of those two options are available
     *
     * @param string $requestUrl
     * @return string
     * @throws Exception
     */
    protected function _getResponse($requestUrl)
    {
        if (ini_get('allow_url_fopen') === "1") {
            return file_get_contents($requestUrl);
        }
        elseif (extension_loaded('curl')) {
            $curl = curl_init($requestUrl);
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            return curl_exec($curl);
        }
        else {
            throw new Exception(
                'Tumblr needs either the Curl library installed or the config setting allow_url_fopen to be 1 in' .
                ' order to perform calls to the Tumblr API.'
            );
        }
    }
}
