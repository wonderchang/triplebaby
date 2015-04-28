<?php
date_default_timezone_set("Asia/Taipei");
require_once('./db-config.php');
require_once('./util.php');

$region_id = filter_escape($_POST['region']);
$subject_id = filter_escape($_POST['subject']);
$content = filter_escape($_POST['content']);
$anonymous = filter_escape($_POST['anonymous']);
$cookie = filter_escape($_POST['cookie']);
$picture1 = filter_escape($_POST['picture1']);
$picture2 = filter_escape($_POST['picture2']);
$picture3 = filter_escape($_POST['picture3']);

$result = mysql_query("SELECT id FROM user WHERE cookie='$cookie'"); 
$num = mysql_num_rows($result);

if($num != 1) { echo 2; return; }

$row = mysql_fetch_assoc($result);
$user_id = $row['id'];
$result = mysql_query("INSERT INTO report SET
  user_id=$user_id,
  region_id=$region_id,
  subject_id=$subject_id,
  content='$content',
  progress_id=0,
  picture1='$picture1',
  picture2='$picture2',
  picture3='$picture3',
  anonymous=$anonymous,
  report_time=now()
  ");

if(!$result) { echo 2; return; }

$result = mysql_query("SELECT * FROM region WHERE region_id=$region_id");
$row = mysql_fetch_assoc($result);
$police_code = $row['police_code'];

$result = mysql_query("SELECT * FROM subject WHERE subject_id=$subject_id");
$row = mysql_fetch_assoc($result);
$subject_name = $row['subject_name'];

$result = mysql_query("SELECT * FROM session ORDER BY session_id DESC LIMIT 1");
$row = mysql_fetch_assoc($result);
$verify_code = $row['verify_code'];
$session = $row['cookie_key'].'='.$row['cookie_value'];

$data = array(
  'nfile1'   => '',
  'nfile2'   => '',
  'nfile3'   => '',
  'name'     => $reporter,
  'sex'      => '2',
  'email'    => $reporter_email,
  'tel'      => '請輸入電話',
  'job'      => '請輸入職業',
  'address'  => '請輸入地址',
  'subject'  => $subject_name,
  'region'   => $police_code,
  'content1' => '',
  'content'  => $content,
  'chkint'   => $verify_code
);
if($picture1 !== '') { $data['nfile1'] = '@'.realpath("../$picture1"); }
if($picture2 !== '') { $data['nfile2'] = '@'.realpath("../$picture2"); }
if($picture3 !== '') { $data['nfile3'] = '@'.realpath("../$picture3"); }

$url = 'http://www.tnpd.gov.tw/chinese/home.jsp?menudata=TncgbMenu&mserno=201012130066&serno=201012130069&serno3=&serno4=&contlink=ap/mail1_save_new.jsp';
$user_agent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_COOKIE, $session);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$response_file = "../response/$cookie-".time().'.html';
$fp = fopen($response_file, 'w');
fwrite($fp, $response);
fclose($fp);
curl_close($ch);

if(!preg_match("/.*HTTP\/1\.1 200 OK.*/", $response)) { echo 0; return; }

$pattern = "/.*案號<\/label><\/th>\s*<td align=\"left\" class=\"font09\">(\d+)<\/td>.*/";
if(!preg_match($pattern, $response, $match)) { echo 0; return; }
$case_id = $match[1];

$pattern = "/.*申辦時間<\/label><\/th>\s*<td align=\"left\" class=\"font09\">(\d\d\d\d-\d\d-\d\d).*?(\d\d:\d\d:\d\d)<\/td>.*/";
if(!preg_match($pattern, $response, $match)) { echo 0; return; }
$apply_time = $match[1].' '.$match[2];

$result = mysql_query("UPDATE report SET
  response_file='$response_file',
  apply_time='$apply_time',
  case_id='$case_id'
  WHERE
  user_id=$user_id AND
  region_id=$region_id AND
  subject_id=$subject_id AND
  content='$content' AND
  picture1='$picture1' AND
  picture2='$picture2' AND
  picture3='$picture3' AND
  anonymous=$anonymous
  ");

if(!$result) { echo 0; return; }

echo 1;
?>
