<?php

//error_reporting(0);
//ini_set('display_errors', 0);

			/// includ database si ma conectez ///
			
			include 'db.inc.php';
			$dbhost = 'localhost';
			$dbuser = 'doarbitcoin';
			$dbpass = 'gigikent39';
			$dbname = 'doarbitcoin';
			$db = new db($dbhost, $dbuser, $dbpass, $dbname);
			
			/// includ database si ma conectez ///

if(isset($_SERVER["HTTP_CF_IPCOUNTRY"])){ $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"]; }
$user_ip = (isset($_SERVER["HTTP_CF_CONNECTING_IP"])?$_SERVER["HTTP_CF_CONNECTING_IP"]:$_SERVER['REMOTE_ADDR']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Nostr Verification</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="icon" type="image/png" href="favicon.ico" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/util.css">
<link rel="stylesheet" type="text/css" href="css/main.css">

<meta name="robots" content="noindex, follow">
</head>
<body>


<?php 
// Google reCAPTCHA API key configuration 
$siteKey     = "recaptcha_pub_key"; 
$secretKey     = "recaptcha_secret_key"; 

 
$postData = $statusMsg = $valErr = ''; 
$status = 'error'; 
 
// Daca e trimis formular 
if(isset($_POST['submit'])){ 
    // Preluare post 
    $postData = $_POST; 
    $username = trim($_POST['username']); 
    $hex = trim($_POST['hex']); 
    $pub = trim($_POST['pub']); 
    $twitter_url = trim($_POST['twitter_url']); 
    $comments = trim($_POST['comments']); 
     
    // Validam campuri 
    if(empty($username)){ 
        $valErr .= 'Please enter your Nostr username.<br/>'; 
    } 
    if(empty($hex)){ 
        $valErr .= 'Please enter your pub key.<br/>'; 
    } 


//// Chestii related to DB

			$verificam_daca_exista = $db->query('SELECT * FROM users WHERE username = ?', $username);
			$numar=$verificam_daca_exista->numRows();
			if($numar==0) {
			} else {
	        $valErr .= 'This username (<b>'.$username.'</b>) is already registred with us, please choose other username.<br/>'; 
			}

//// Chestii related to DB

     
    if(empty($valErr)){ 
         
        // Validare reCAPTCHA box
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){ 
 
            // Verificare raspuns reCAPTCHA 
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']); 
             
            $responseData = json_decode($verifyResponse); 
             
            // Daca e valid 
            if($responseData->success){ 
                 
                $status = 'success'; 
                $statusMsg = 'Thank you, your submission has been received! <br/> Please note that it may take 10 to 15 minutes to spread to the nodes.'; 
                $postData = ''; 


            }else{ 
                $statusMsg = 'Robot verification failed, please try again.'; 
            } 
        }else{ 
            $statusMsg = 'Please check on the reCAPTCHA box.'; 
        } 
    }else{ 
        $statusMsg = '<p class="text-danger"><b>Please fill all the mandatory fields:</b></p>'.trim($valErr, '<br/>'); 
    } 
}

// Afisam ce se intampla 
//echo $statusMsg;

?>






<div class="container-nostr">
  <div class="wrap-nostr">

<?php
if ($status =="success") {
?>




      <span class="nostr-form-title"> Nostr Verification </span>

<?php if (!empty($statusMsg)) { ?>
        <div class="wrap">
          <div class="col-12 col-sm-12">
        	<span class="label text-danger"><?php echo $statusMsg; ?></span>
          </div>
        </div>
<?php } ?>

        <div class="wrap">
          <div class="col-12 col-sm-12">
			<span class="label text-success"><h2>Success!</h2></span>
        	<span class="label">
	        <?php

//// Chestii related to DB

		$verificam_daca_exista = $db->query('SELECT * FROM users WHERE hex = ?', $hex);
		$numar=$verificam_daca_exista->numRows();
		if($numar==0) {

		$adauga = $db->query('INSERT INTO users (username,pub,hex,twitter_url,country_code,comments) VALUES (?,?,?,?,?,?)', $username, $pub, $hex, $twitter_url, $country_code, $comments);

		} else {

		// fara verificare exista risc ca cineva sa atribuie alt pubkey la un user deaja inregistrat
		//$updateauser = $db->query('UPDATE users SET username = ? WHERE pub = ?', $username, $pub);

		}

			$db->close();
//// Chestii related to DB


	            echo "<h3>NIP-05 Nostr verification details:</h3>";
	            echo "Username: <b>".$username."</b> @ doarbitcoin.com";
	            echo "<br>";
	            echo "Pub key: <b>".$pub."</b>";
	            echo "<br>";
	            echo "Hex key: <b>".$hex."</b>";
	            echo "<br>";
	            echo "Comments: ".$comments;
	            echo "<br>";
	        ?>
			</span>
          </div>
        </div>

      <div class="container-nostr-form-btn">
        <div class="wrap-nostr-form-btn">
          <div class="nostr-form-bgbtn"></div>
          <button class="nostr-form-btn" onclick="history.back()">
            <span>Back</span>
          </button>
        </div>
      </div>

      </div>



<?php
} else { 
?>


    <form class="nostr-form validate-form" name="form" method="post" action="<?php echo htmlspecialchars($_SERVER[‘PHP_SELF’]); ?>">
      <span class="nostr-form-title"> Nostr Verification </span>
      <div class="wrap">


<?php if (!empty($statusMsg)) { ?>
        <div class="wrap">
          <div class="col-12 col-sm-12">
        	<span class="label text-danger"><?php echo $statusMsg; ?></span>
          </div>
        </div>
<?php } ?>





        <span class="label">Nostr username (required)</span>
        <div class="row">
          <div class="col-12 col-sm-8">
            <input type="text" name="username" class="form-control form-input" placeholder="username" maxlength="500">
          </div>
          <div class="col-12 col-sm-4">
            <div class="selectdiv">
              <select name="domain" class="form-control">
                <option value="default" selected="">@ doarbitcoin.com</option>
              </select>
            </div>
          </div>
        </div>
        <span class="focus"></span>
      </div>
      <div class="wrap">
        <span class="label">Nostr pub key (required)</span>
        <input class="input100" type="text" name="pub" placeholder="Enter your npub... OR nsec... key here" id="damus-key">
        <span class="focus"></span>
        <span class="label">
          <b>WARNING:</b> make sure you are inputting your pub key and not your private key. </span>
      </div>


      <div class="wrap">
        <span class="label">Hex key (nothing to do here)</span>
        <input class="input100" type="text" name="hex-gen" value="" placeholder="Auto-generated hex key based on Nostr pub key" id="hex-gen" disabled>
		<input type="hidden" name="hex" value="" id="hex-key">  
		<input type="hidden" name="nostr_url" value="" id="note-link">  
        <span class="focus"></span>
      </div>


      <div class="wrap">
        <span class="label">Twitter username (optional)</span>
        <input class="input100" type="text" name="twitter_url" value="@" placeholder="Optional your twitter @username">
        <span class="focus"></span>
      </div>

      <div class="wrap">
        <span class="label">Comments (optional)</span>
        <textarea class="input100" name="comments" placeholder="Your message here..."></textarea>
        <span class="focus"></span>
      </div>

		<div class="text-xs-center">
		<div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
		</div>

      <div class="container-nostr-form-btn">
        <div class="wrap-nostr-form-btn">
          <div class="nostr-form-bgbtn"></div>
          <button class="nostr-form-btn" name="submit">
            <span> Submit <i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
            </span>
          </button>
        </div>
      </div>
    </form>

<?php
}
?>




  </div>
</div>








<div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-4 col-lg-6 image-wrapper">
                <div class="mbr-img-wrap">
                <img src="elon.jpg" alt="" style="width:284px">
                </div>
            </div>
            <div class="col-8 col-lg-6">
                <div class="text-wrapper">

					<span class="small">
					<h3>The final step!</h3> <br>In your NOSTR client <br>(in our case, damus) navigate to your profile settings <br>and in the <b>NIP-05 Verification</b> box type the username you used, <br>followed by our custom domain (@doarbitcoin.com). <br>In our case it is <b>elonmusk@doarbitcoin.com</b>
					<br><br>
					Click <b>save</b> and reload the profile page <br>(sometimes opening and closing the app will help) <br>Your verified username should appear with our custom domain now!
					<br><br>
					You can find a list of good relays here :<br> <a href="https://nostr.watch/relays/find" target="_blank"><b>https://nostr.watch/relays/find</b></a><br>

					Nostr Apps : <br>
					Android : &nbsp;&nbsp;<a href="https://play.google.com/store/apps/details?id=com.vitorpamplona.amethyst" target="_blank"><b>Amethyst</b></a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; iOS : &nbsp;&nbsp;<a href="https://apps.apple.com/ca/app/damus/id1628663131" target="_blank"><b>Damus</b></a><br>
					</span>

                </div>
            </div>
        </div>
</div>



















<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="js/bech32.js" ></script>
<script src="js/key.js?v=3" ></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
<?php  $db->close(); ?>
