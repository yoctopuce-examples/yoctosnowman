Yoctopuce Snowman Chrismas ornement
===================================

The holiday season is starting presently and it is more than time to take out Christmas decorations. While rummaging in the attic,  we happened on an old ornament in the shape of a snowman. We thought that there had to be a way to make this basic ornament into something much more interesting. And what if YOU could control our Christmas ornament with a Tweet?  Let's see how to put this idea into practice…

We are going to replace the light bulb with two <product>Yocto-PowerColor</product> secured to a printed support (made with a Makerbot). On top of consuming less electricity, this enables us to change the color of the ornament. To control the two <product>Yocto-PowerColor</product>, we use a <product>YoctoHub-Ethernet</product>. As there is still an empty port on the <product>YoctoHub-Ethernet</product>, we also connect a <product>Yocto-Light</product> to switch off the leds in the daytime.

![Alt The snowman, after reassembly](http://www.yoctopuce.com/pubarchive/2013-12/snowman_1.jpg)

There are two advantages to using a YoctoHub: First, using the PHP library in callback mode,  we avoid having an additional machine dedicated to the control of the leds. We simply put our PHP script on our web server, and the Hub contacts our script every 10 seconds to retrieve the color of the two leds.

![Alt The two leds fixed to the 3d printed support](http://www.yoctopuce.com/pubarchive/2013-12/ledfix_1.jpg)

Secondly, most people think that using wifi to connect an appliance located in the garden is simpler. However, this implies having a power source at hand. And there is no socket where we want to put our snowman. Using a PoE (Power over Ethernet) Ethernet switch, we need only one Ethernet category 5 cable in the garden. An Ethernet cable can be as long as a hundred meters and is much simpler to install than an extension cord.

As the modules are compact enough, it's very easy to put all this hardware into the snowman. To ease assembly, we detached the two leds and the light sensor from their board and used ribbon cable to connect them. This enabled us to group all the modules into a very compact small block. You must however make sure that the boards are assembled vertically so that potential dew drops can run and don't stay on the board.

![Alt It is important to assemble the Yoctopuce modules vertically to prevent water drops to accumulate](http://www.yoctopuce.com/pubarchive/2013-12/boardfix_1.jpg)

We must configure the <product>YoctoHub-Ethernet</product> to call our script every ten seconds. As a reminder, we must connect ourselves to the <product>YoctoHub-Ethernet</product> web interface and enter the script URL in the callback configuration window. Beware, Twitter limits the number of requests to 180 per quarter of an hour. For example, if we call the script every second, the script will work only 3 minutes before it is blocked for the next 12 minutes.
 
![Alt We must configure the YoctoHub-Ethernet to call the script every 10 seconds](http://www.yoctopuce.com/pubarchive/2013-12/config_1.jpg)

We must start the script by a call to <tt>yRegisterHub</tt> with the 'callback' keyword for the API to work in <a href="https://www.yoctopuce.com/EN/products/yoctohub-ethernet/doc/YHUBETH1.usermanual.html#CHAP7SEC3">Yocto-API callback</a> mode.

    ...
    $errmsg = "";
    if(yRegisterHub('callback', $errmsg) != YAPI_SUCCESS) {
        print("Unable to start the API in callback mode ($errmsg)");
        die();
    }

    $top_led = yFindColorLed('TopLed');
    $bottom_led = yFindColorLed('BottomLed');
    ...

To retrieve a list of Tweets containing a specific hashtag, it's a little more complex. From version 1.1 of the API, we must authenticate ourselves to use the REST API. We use the "mini" library <a href="https://github.com/J7mbo/twitter-api-php"> twitter-api-php</a> to avoid needing to manage the authentication. We must also create a Twitter developer account to retrieve authentication keys. The process is well documented in <a href="http://stackoverflow.com/questions/12916539/simplest-php-example-for-retrieving-user-timeline-with-twitter-api-version-1-1/15314662#15314662">this post</a>.

When reading the <a href="https://dev.twitter.com/docs/api/1.1/get/search/tweets"> documentation</a>,  we found that the request to retrieve the latest Tweets with the <tt>#yoctosnowman</tt> hashtag is an HTTP GET request to <tt>https://api.twitter.com/1.1/search/tweets.json</tt>, providing the <tt>q=#yoctosnowman</tt> parameter. You can even test it with <a href="https://twitter.com/search?src=typd&q=%23yoctosnowman">https://twitter.com/search?src=typd&q=%23yoctosnowman</a>.

In the end, the PHP code to retrieve the list of Tweets containing the <tt>#yoctosnowman</tt> hashtag is the following:

    ...
    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
        'oauth_access_token' => "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
        'oauth_access_token_secret' => "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
        'consumer_key' => "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
        'consumer_secret' => "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
    );
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $getfield = '?q=#yoctosnowman';
    $requestMethod = 'GET';
    $twitter = new TwitterAPIExchange($settings);
    $json = $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();
    $result = json_decode($json);
    ...

Naturally, you must replace the <tt>XXX</tt> with your own keys. When we have the list of Tweets, we only need to update the two leds depending on the text of the last Tweet.

    ...
    foreach ($result->statuses as $tweet) {
        $tweet_msg = utf8_decode($tweet->text);
        ...
        //parse message to find color
        ...
        if ($colorFound) {
            // set color for two Yocto-PowerColor
            $top_led->set_rgbColor($colors[0]);
            $bottom_led->set_rgbColor($colors[1]);
            break;
        }
    }
    ...

As usual, you can find <a href="http://github.com/yoctopuce-examples/yoctosnowman">the complete project on  GitHub</a>.

For our Yocto Snowman, we detect <a href="http://www.w3schools.com/html/html_colornames.asp">the standard HTML colors written in English</a>. If there is only one color, we use it for the head and the body of the snowman. If there are two colors, we use the first one for the head and the second one for the body.

