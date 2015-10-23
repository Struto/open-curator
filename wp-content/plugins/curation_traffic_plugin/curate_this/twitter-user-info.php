<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <title>sticky option</title>
</head>
<body class="page">
<?php 
function numberFormat($num) 
{ 
     return preg_replace("/(?<=\d)(?=(\d{3})+(?!\d))/",",",$num); 
} 
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: foo=bar\r\n"
  )
);

//klout api key: 5jfc4hyrmjcnwvach6z2xep6
$titleDisplay ='';
$twitter_user_name = '';
$klout_score='';
$profile_image ='';
$statuses_count = '';
$twitter_user_name = $_GET["twitterUserName"];
$supportURL = $_GET["supportURL"];
$supportURL = urldecode($supportURL);
/*$url = "http://twitter.com/users/show/" . $twitter_user_name;
//echo $url;
$response = @file_get_contents ( $url );
//echo 'response: ' . $response;	
//echo 'strlen: ' . strlen($response);		
if(isset($response) && strlen($response) > 0) { 

	$t_profile = new SimpleXMLElement ( $response );
	$followers_count = $t_profile->followers_count;
	$friends_count = $t_profile->friends_count;
	
	$desc = $t_profile->description;
	$profile_image = $t_profile->profile_image_url;
	$statuses_count = $t_profile->statuses_count;
	$listed_count = $t_profile->listed_count;
	$status_text  = $t_profile->status->text;
	$status_id  = $t_profile->status->id;
	$user_url = $t_profile->url;
	
	//api.klout.com/1/klout.xml?users=scottscanlon&key=5jfc4hyrmjcnwvach6z2xep6
}
	$kloutURL = "http://api.klout.com/1/klout.xml?users=".$twitter_user_name."&key=q8ab38qhgfxhbat96fpdhufa";
	$response = @file_get_contents ( $kloutURL );
	//echo 'response: ' . $response;			
	if(isset($response) && strlen($response) > 0 && strpos($response,'No users') === false)
	{
		$k_profile = new SimpleXMLElement ( $response );
		$klout_score = $k_profile->user->kscore;
	}
	else
	{
		$klout_score = "--";
	}
*/	
$api_key = 'q8ab38qhgfxhbat96fpdhufa';
//$twitter_user_name = 'scottscanlon';
$id_url = 'http://api.klout.com/v2/identity.json/twitter?screenName='. $twitter_user_name .'&key='. $api_key;
$json = file_get_contents($id_url);
$id = json_decode($json, TRUE);
$kloutid = $id['id'];
$score_url = 'http://api.klout.com/v2/user.json/'. $kloutid .'/score?key='. $api_key;
$json = file_get_contents($score_url);
$score = json_decode($json, TRUE);
$klout_score = round($score['score']);
$topics_url = 'http://api.klout.com/v2/user.json/'. $kloutid .'/topics?key='. $api_key;
$json = file_get_contents($topics_url);
$topics = json_decode($json, TRUE);

$influence_url = 'http://api.klout.com/v2/user.json/'. $kloutid .'/influence?key='. $api_key;
$json = file_get_contents($influence_url);
$influencers = json_decode($json, TRUE);



?>
<div id="twitter_info_w">
	<div id="pic_w">
    	<div id="pic_inner">
	   	<p><a href="http://www.twitter.com/#!/<?php echo $twitter_user_name; ?>" target="_blank"><strong><i class="fa fa-twitter fa-lg"></i> Visit <?php echo $twitter_user_name; ?></strong></a></p>
        <div style="clear: both; height:0;"></div>
        </div>
    </div>
    <div id="bottom_w">
    	<div id="info_w">
            <div>
                <div style="margin-bottom: 5px;"><img src="<?php echo urldecode($supportURL); ?>/i/klout.png" style="margin-bottom: -9px;"  />
                <strong> <a href="http://klout.com/#/<?php echo $twitter_user_name; ?>" target="_blank"><?php echo $twitter_user_name; ?></a></strong>
                <strong>Klout Score</strong>: <?php echo $klout_score; ?></div>
            </div>
            <div style="clear: both; width: 100%; padding-top: 10px;">
            <strong>Key Topics</strong>:

<?php 
				$total = count($topics);
				$i = 1;
				foreach($topics as $item)
				{
				
					$uses = $item['displayName'];
					echo $uses;
					if($i < $total)
						echo ", ";
					$i++;
				} 
?>
            </div>
        </div>
            <div style="clear: both; width: 100%; padding-top: 10px;">
            <strong>Who Influences Them</strong>:
				<?php 
                
                $WhoInfluencesThem =$influencers['myInfluencers'];
                //myInfluencees
				$total = count($WhoInfluencesThem);
				$i = 1;
                foreach($WhoInfluencesThem as $item)
                {

						
                   $user = $item['entity']['payload']['nick'];
                    $score = $item['entity']['payload']['score']['score'];
                    echo '<a href="http://klout.com/#/' . $user . '" target="_blank">'. $user . '</a>' . ' ('. round($score) . ')';
					if($i < $total)
						echo ", ";
					$i++;
                } 
                ?>
            </div>
            <div style="clear: both; width: 100%; padding-top: 10px;">
            <strong>Who They Influence</strong>:
				<?php 
                
                $WhoTheyInfluence =$influencers['myInfluencees'];
				$total = count($WhoTheyInfluence);
				$i = 1;
                foreach($WhoTheyInfluence as $item)
                {


                    $user = $item['entity']['payload']['nick'];
                    $score = $item['entity']['payload']['score']['score'];
                    echo '<a href="http://klout.com/#/' . $user . '" target="_blank">'. $user . '</a>' . ' ('. round($score) . ')';
					if($i < $total)
						echo ", ";
					$i++;
                } 
                ?>
                <br /><br />


            </div>
        </div>
    </div>
</div>

</body>
</html>