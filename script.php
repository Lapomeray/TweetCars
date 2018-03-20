
<?php
session_start();

require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;


/**
 * Date: 10/16/17
 * Time: 12:03 PM
 * The purpose of this program is to tweet posts related to cars and motorcycles
 * Images and Captions are pulled from Pinterest boards and stored in a JSON file
 */

// Set Session variable
$i = isset($_SESSION['i']) ? $_SESSION['i'] : 0;

$_SESSION['i'] = $i;

date_default_timezone_set("America/New_York");

// Date and time
echo "Today is " . date("m/d/Y") . "<br>";
echo "The time is " . date("h:i:sa");
echo "<br>";


// This function will take in a title and return a string with appended hashtags
function Caption($title){

    //array of hashtags
    $hashtagArray = array(

        array("#red "),
        array("#sporty "),
        array("#adventure "),
        array("#fastcar "),
        array("#racetrack "),
        array("#needForSpeed "),
        array("#lifestyle "),
        array("#mph "),
        array("#streaks "),
        array("#workhard "),
        array("#playharder "),
        array("#spectacularstudio "),
        array("#streetview "),
        array("#amazing_cars "),
        array("#supercars "),
        array("#likeforlike "),
        array("#hypercar "),
        array("#l4l ")



    );



    //Twitter character limit
    $limit = 140;

    // Character length of title
    $title_length = strlen($title);
   // echo "The title length is ". $title_length;
   // echo"<br>";



    // Characters left minus the title
    $characterLeftToUse = $limit - $title_length;
   // echo "# of Characters left ". $characterLeftToUse;
   // echo"<br>";




    // # of hastags
    $numberOfHashtags = count($hashtagArray);
    //echo "Number of hashtags is ". $numberOfHashtags;
    //echo"<br>";


    // Need to look for hashtags at random, add them to array to be added to caption, for every hashtag added
    // Subtract that length from characters left to use.. Once the value becomes negative drop the last added
    //hastag. Then append all the values of the array to caption.


    //array holder
    $a = array();


    while ($characterLeftToUse > 0){

        // Genertes random number
        $r = mt_rand(0,$numberOfHashtags - 1);
        // Choose random hashtag
        $rh = $hashtagArray[$r][0];

        // Decrement the $characterslefttouse by the hashtag letter count
        $characterLeftToUse -= strlen($rh);

        // Check if letter count is positive before adding to array
        if ($characterLeftToUse > 0) {
            // adds random hashtag to holder array
            array_push($a, $rh);
        }

    }



    // Make hashtag array a string
    $htString =  implode("",$a);

    $caption =  $title." ".$htString;


    return $caption;
}



// The tweet function
function tweet($x){


    $url = 'CarsandMotorcycles.json';
    $d = file_get_contents($url);
    $tweets = json_decode($d);

    //Creates title with out link
    $title_raw = explode("<",$tweets[$x]->title);

    // shortens text to 140 characters
    $title = substr($title_raw[0],0,140);

    // Gets URL
    $url = $tweets[$x]->url;


    // get Final tweet
    $finalTweet = Caption($title);





    //Prints them out
    echo "<br>";

    //Title
    echo "Tweet: ". $finalTweet;
    echo "<br>";
    echo "<br>";

    //URL
    echo "Image used: ". $url;
    echo "<br>";
    echo "<br>";

    // Character count of Tweet
    echo "The Character count is " . strlen($finalTweet);
    echo "<br>";
    echo "<br>";

    /** TWITTER API */

// Credentials


    $ck = '##############';
    $cs = '##############';

    $at = '##############';
    $ats = '##############';

// Creates Connection
$connection = new TwitterOAuth

(   $ck,
    $cs,
    $at,
    $ats

);


//Uploads media to get ID
    $media1 = $connection->upload('media/upload',
        ['media' => $url]);

//Constructs parameters
    $parameters = [
        'status' => $finalTweet,
        'media_ids' => implode(',', [$media1->media_id_string])
    ];

/** Executes Tweet */
//$result = $connection->post('statuses/update', $parameters);

// Needs to return the tweet number
    return $x;
}



/** Calls the function and echos the tweet number */
$number = tweet(++$_SESSION['i']);

// Gives the real tweet number adjusted for the array protocol which starts at zero
$number_real = $number + 1;




// Prints confimation
echo "Cars account tweeted post number ". $number_real;
?>


