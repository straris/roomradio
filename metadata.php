<?php
header('Access-Control-Allow-Origin: *');
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('connect_to_mysql.php');

/*
 * SCRIPT CONFIGURATIONS
*/
//$SERVER = 'http://83.212.116.204:8000'; //URL TO YOUR ICECAST SERVER
$SERVER = 'http://localhost:8000'; //URL TO YOUR ICECAST SERVER
$STATS_FILE = '/status.xsl'; //PATH TO STATUS.XSL PAGE YOU CAN SEE IN YOUR BROWSER (LEAVE BLANK UNLESS DIFFERENT)


//create a new curl resource
$ch = curl_init();

//set url
curl_setopt($ch,CURLOPT_URL,$SERVER.$STATS_FILE);

//return as a string
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

//$output = our stauts.xsl file
$output = curl_exec($ch);

//close curl resource to free up system resources
curl_close($ch);

//build array to store our radio stats for later use
$radio_info = array();
$radio_info['server'] = $SERVER;
$radio_info['title'] = '';
$radio_info['description'] = '';
$radio_info['content_type'] = '';
$radio_info['mount_start'] = '';
$radio_info['bit_rate'] = '';
$radio_info['listeners'] = '';
$radio_info['most_listeners'] = '';
$radio_info['genre'] = '';
$radio_info['url'] = '';
$radio_info['now_playing'] = array();
   $radio_info['now_playing']['artist'] = '';
   $radio_info['now_playing']['track'] = '';

//loop through $ouput and sort into our different arrays
$temp_array = array();

$search_for = "<td\s[^>]*class=\"streamdata\">(.*)<\/td>";
$search_td = array('<td class="streamdata">','</td>');

if(preg_match_all("/$search_for/siU",$output,$matches)) {
   foreach($matches[0] as $match) {
      $to_push = str_replace($search_td,'',$match);
      $to_push = trim($to_push);
      array_push($temp_array,$to_push);
   }
}

//sort our temp array into our ral array
$radio_info['title'] = $temp_array[0];
$radio_info['description'] = $temp_array[1];
$radio_info['content_type'] = $temp_array[2];
$radio_info['mount_start'] = $temp_array[3];
$radio_info['bit_rate'] = $temp_array[4];
$radio_info['listeners'] = $temp_array[5];
$radio_info['most_listeners'] = $temp_array[6];
$radio_info['genre'] = $temp_array[7];
$radio_info['url'] = $temp_array[8];
$onAir="false";
if (count($temp_array)>9){
	if ($temp_array[9]=="onAir")
	$onAir="true";
}

$x = explode(" - ",$temp_array[8]);
if (isset($x[0]))
$radio_info['now_playing']['artist'] = $x[0];
if (isset($x[1]))
$radio_info['now_playing']['track'] = $x[1];

$metadata = $radio_info['now_playing']['artist']." - ".$radio_info['now_playing']['track'];


$imageQuery=str_replace(' ', '%20', $radio_info['now_playing']['artist']);
$imageQuery=$imageQuery."%20artwork";

function get_url_contents($url) {
    $crl = curl_init();

    curl_setopt($crl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
    curl_setopt($crl, CURLOPT_URL, $url);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 5);

    $ret = curl_exec($crl);
    curl_close($crl);
    return $ret;
}


// print "$imageQuery";

$q="SELECT * FROM images WHERE image='$imageQuery'";
$r=mysql_query($q) or die ("could not check the user due to:".mysql_error());

$answers=mysql_num_rows($r);

if ($answers==0)
{
	$url ='https://www.googleapis.com/customsearch/v1?key=AIzaSyAliM8GjxXcPKMkZiPiDxlGhLI25mZHhsU&cx=016998421560717524457:0bctl8e-qsm&q='. trim($imageQuery).'&searchType=image&fileType=jpg&alt=json';
	function get_url_contents2($url){
       		$crl = curl_init();
        	$timeout = 5;
        	curl_setopt ($crl, CURLOPT_URL,$url);
       		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
        	curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
        	$ret = curl_exec($crl);
	        curl_close($crl);
		return $ret;
	}
	$json = get_url_contents2($url);
	$data = json_decode($json);	
	if ($json!=null){
		$i = 0;
		foreach ($data->items as $item) {
			if ($i++ > 0) break;
			$contents= $item->link;
		}
		
		$sql = mysql_query("INSERT INTO images (image, image_url) VALUES ('$imageQuery', '$item->link')") or die (mysql_error());
	}
} 
else
{
	while ($arr=mysql_fetch_array($r)) {
		$contents=$arr['image_url'];
		
		}
	
}


$pointer = file_get_contents('http://83.212.116.204/pointer.txt');


if ($contents != null and $pointer=='random')
{

file_put_contents("image.jpg", fopen($contents, 'r'));
}

else
{

$file ='image2.jpg';
$newfile = 'image.jpg';
copy($file, $newfile);
}


print "metaData=$metadata onAir=$onAir";

?>
