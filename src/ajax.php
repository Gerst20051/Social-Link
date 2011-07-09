<?php
session_start();

if (isset($_GET['p']) && $_GET['p'] == 'logged') {
	if (isset($_SESSION['logged'])) die('true'); else die('0');
}

include 'validip.inc.php';
include 'mysql.class.php';

function html($role,$text,$fixed=false,$left=false,$right=false) {
	function button($data) {
		$button = '<a href="'.$data[0].'"';
		if (sizeof($data) == 2 && $data[1] === true) $button .= ' data-icon="back" data-rel="back">Back</a>';
		elseif (sizeof($data) == 2) $button .= '>'.$data[1].'</a>';
		elseif (sizeof($data) == 3) $button .= ' data-icon="'.$data[1].'">'.$data[2].'</a>';
		return $button;
	}
	$output = '<div data-role="'.$role.'"';
	if ($fixed) $output .= ' data-position="fixed"';
	$output .= '>';
	if ($left) $output .= button($left);
	$output .= '<h1>'.$text.'</h1>';
	if ($right) $output .= button($right);
	$output .= '</div>';
	echo $output;
}

function loggedIn() {
	$output = '<div data-role="page" data-theme="b" id="loggedin">';
	$output .= html('header','Social Link');
	$output .= '<div data-role="content">';
	$output .= 'Logged In!! '.$_SESSION['username'];
	$output .= '<a href="#" data-role="button" id="logout">Logout</a>';
	$output .= '</div>';
	$output .= '</div>';
	echo $output;
}

function logOut() {
	session_unset();
	session_destroy();
}

if (isset($_SESSION['logged'])) {
loggedIn();
if (isset($_POST['logout'])) {
logOut();
}
} else {
if (isset($_POST['login'])) {
try {
	$db = new MySQL();
	$db->sfquery('SELECT * FROM login u JOIN info i ON u.user_id = i.user_id WHERE username = %s AND password = PASSWORD(%s)',array('username'=>$_POST['username'],'password'=>$_POST['password']));
	if ($db->numRows() > 0) {
		$db->fetchAssocRow();
		$_SESSION['logged'] = true;
		$_SESSION['username'] = $row['username'];
		loggedIn();
	}
} catch(Exception $e) {
	echo $e->getMessage();
	exit();
}
}
if (isset($_POST['register'])) {
$username = (isset($_POST['username'])) ? $_POST['username'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';
$name = (isset($_POST['name'])) ? ucname($_POST['fullname']) : '';
$email = (isset($_POST['email'])) ? $_POST['email'] : '';
$hometown = (isset($_POST['hometown'])) ? $_POST['hometown'] : '';
$community = (isset($_POST['city'])) ? $_POST['city'] : '';
$gender = (isset($_POST['gender'])) ? $_POST['gender'] : '';
$bmonth = (isset($_POST['bmonth'])) ? $_POST['bmonth'] : '';
$bday = (isset($_POST['bday'])) ? $_POST['bday'] : '';
$byear = (isset($_POST['byear'])) ? $_POST['byear'] : '';
if (empty($community)) $community = $hometown;
list($firstname, $middlename, $lastname) = split(' ',$name);
if (!$lastname) { $lastname = $middlename; unset($middlename); }
try {
	$db = new MySQL();
	$db->insert(array('username'=>$username,'password'=>$password,'access_level'=>1,'last_login'=>date('Y-m-d'),'date_joined'=>date('Y-m-d'),'last_login_ip'=>$ip),'login');
	$db->insert(array('user_id'=>$db->insertID(),'firstname'=>$firstname,'middlename'=>$middlename,'lastname'=>$lastname,'email'=>$email,'gender'=>$gender,'hometown'=>$hometown,'community'=>$community,'birth_month'=>$bmonth,'birth_day'=>$bday,'birth_year'=>$byear,'logins'=>1));
} catch(Exception $e) {
	echo $e->getMessage();
	exit();
}
loggedIn();
}
if (isset($_GET['p'])) {
if ($_GET['p'] == 'username') {
try {
	$db = new MySQL();
	$db->query('SELECT username FROM login WHERE username = "'.$_GET['username'].'"');
	echo $db->numRows();
} catch(Exception $e) {
	echo $e->getMessage();
	exit();
}
} elseif ($_GET['p'] == 'register') {
?>
<div data-role="page" data-theme="b" id="register">
<?php html('header','Social Link',false,array('#splash',true)) ?>
<div data-role="content">
<form action="#" method="post" id="f_register">
<input type="hidden" name="register"/>
<div data-role="fieldcontain">
<label for="username">Username:</label>
<input type="text" name="username" id="username" value=""/>
</div>
<div data-role="fieldcontain">
<label for="password">Password:</label>
<input type="password" name="password" id="password" value=""/>
</div>
<div data-role="fieldcontain">
<label for="name">Full Name:</label>
<input type="text" name="name" id="name" value=""/>
</div>
<div data-role="fieldcontain">
<label for="email">Email:</label>
<input type="email" name="email" id="email" value=""/>
</div>
<div data-role="fieldcontain">
<label for="hometown">Hometown:</label>
<input type="text" name="hometown" id="hometown" value=""/>
</div>
<div data-role="fieldcontain">
<label for="city">Community:</label>
<input type="text" name="city" id="city" value="" placeholder="Current Location, School, Business, or Group"/>
</div>
<div data-role="fieldcontain">
<label for="gender">Gender:</label>
<select name="gender" id="gender" data-role="slider">
<option value="male">Male</option>
<option value="female">Female</option>
</select> 
</div>
<div data-role="fieldcontain">
<div class="ui-grid-c">
<div class="ui-block-a">Birth Date:</div>
<div class="ui-block-b">
<select data-native-menu="false" name="bmonth" id="bmonth">
<option value="0">Month</option>
<option value="1">January</option>
<option value="2">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</div>
<div class="ui-block-c">
<select name="bday" id="bday">
<option value="0">Day</option>
</select>
</div>
<div class="ui-block-d">
<select name="byear" id="byear">
<option value="0">Year</option>
</select>
</div>
</div>
</div>
<div class="ui-body ui-body-b">
<a href="#" data-role="button" data-theme="a" id="b_register">Register</a>
</div>
</form>
</div>
<script>
(function(){
var bday = "", byear = "";
for (i = 1; i <= 31; i++) { bday += "<option value=\""+i+"\">"+i+"</option>"; }
for (i = 2011; i >= 1902; i--) { byear += "<option value=\""+i+"\">"+i+"</option>"; }
$("#bday").append(bday);
$("#byear").append(byear);
})();
</script>
<?php
}
}
}
?>