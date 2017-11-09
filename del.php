<?php 

if (empty($_GET['id'])) {
	exit('你必须提供要删除的数据ID');
}
$id = $_GET['id'];

var_dump($id);

$json = file_get_contents('data.json');

$songs = json_decode($json,true);

foreach ($songs as $item) {
	if ($item['id'] === $id) {
		$index = array_search($item, $songs);
		array_splice($songs, $index, 1);
		$new_json = json_encode($songs);
		file_put_contents('data.json', $new_json);
		break;
	}
}

header('location: /code5/list.php');