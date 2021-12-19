<?php
function myhash($s,$count){
	$len = strlen($s);
	$n = 0;
	for($i=0;$i<$len;$i++){
		$n = ($n+ord($s{$i})*ord($s{$i}))%$count;
	}
	return $n;
}
function check($s){
	if(strlen($s) < 6) return false;
	return substr($s,0,6) == 'آیا' && ( substr($s,-2) == '؟' || $s{strlen($s)-1} == '?' );
}
$msgs = explode(PHP_EOL,file_get_contents('messages.txt'));
$M_COUNT = count($msgs);
$person_data = file_get_contents('people.json');
$pa = (array)json_decode($person_data);
$pk = array_keys($pa);
$P_COUNT = count($pa);
$en_name = $pk[rand(0,$P_COUNT-1)];
$msg = "سوال خود را بپرس!";
$question = "";
if(isset($_POST['question'])){
	$question = $_POST['question'];
	$en_name = $_POST['person'];
	$msg = $msgs[myhash($question.$en_name,$M_COUNT)];
	if(!check($question))
		$msg = "سوال درستی پرسیده نشده!";
}
$fa_name = $pa[$en_name];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
	<?php
	if(isset($_POST['question'])){
	?>
    <div id="title">
        <span id="label">پرسش:</span>
        <span id="question"><?php echo $question ?></span>
    </div>
	<?php
	}
	?>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
	    <select name="person" value="<?php echo $en_name; ?>">
                <?php
		foreach($pa as $key => $val){
			if($key == $en_name)
				echo "<option value=\"$key\" selected>$val</option>\n";
			else
				echo "<option value=\"$key\">$val</option>\n";
		}
		?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>
