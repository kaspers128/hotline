<?php 

include "../class/class.php";



if (isset($_GET['topic_id']) && ($_GET['topic_id'] != '')){
	$topic_id = $_GET['topic_id'];
}else{
	$topic_id = false;
}

$json = array();

if ($topic_id){
	$topic = new Topic;
	
	$sub_topics = $topic->getSubTopic($topic_id);
	
	$html = "";
	foreach($sub_topics as $sub_topic){
		$sub = $sub_topic['sub_topic_name'];
		$sub = iconv("Windows-1251", mb_detect_encoding($sub), $sub);
		$html .= "<option value='{$sub}'>".$sub."</option>";
	}
	
	$json['result'] = true;
	$json['html'] = $html;
	echo json_encode($json);
	
}else{
	$json['result'] = false;
	echo json_encode($json);
}

?>