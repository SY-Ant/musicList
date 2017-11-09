<?php 
function postback() {
if (empty($_POST['title'])) {
  $GLOBALS['error_type'] = 'title';
  $GLOBALS['error_msg'] = '填写标题';
  return;
}
if (empty($_POST['artist'])) {
  $GLOBALS['error_type'] = 'artist';
  $GLOBALS['error_msg'] = '填写歌手';
  return;
}

// ===== 单文件 =====
if (!(isset($_FILES['source']) && $_FILES['source']['error'] === UPLOAD_ERR_OK)) {
   $GLOBALS['error_type'] = 'source';
    $GLOBALS['error_msg'] = "上传失败";
    return;
}
$allowed_source_types = array('audio/mp3','audio/wma');

if (!in_array($_FILES['source']['type'], $allowed_source_types )) {
  $GLOBALS['error_type'] = 'source';
    $GLOBALS['error_msg'] = "只能上传音频文件";
    return;
}

if (1 * 1024 *1024 > $_FILES['source']['size'] || $_FILES['source']['size'] > 10 * 1024 *1024) {
   $GLOBALS['error_type'] = 'source';
    $GLOBALS['error_msg'] = "上传文件大小不合理";
    return;
}
 
$tmp_path = $_FILES['source']['tmp_name'];
var_dump($_FILES['source']['name']);
$dest_path = './uploads/mp3/' . $_FILES['source']['name'];//以当前为目标,要存放位置
$source = '/code5' . substr($dest_path, 1);
$moved = move_uploaded_file($tmp_path, $dest_path);
var_dump($moved);

if (!$moved) {
  $GLOBALS['error_type'] = 'source';
  $GLOBALS['error_msg'] = "上传失败";
  return;
}

//多文件上传
for ($i=0; $i < count($_FILES['images']['error']); $i++) { 
 if ($_FILES['images']['error'][$i] !==UPLOAD_ERR_OK) {
  $GLOBALS['error_type'] = 'images';
  $GLOBALS['error_msg'] = "上传失败";
  return;
 }
$allowed_images_types = array('image/jpeg','image/png','image/gif');
if (!in_array($_FILES['images']['type'][$i],$allowed_images_types)) {
  $GLOBALS['error_type'] = 'images';
  $GLOBALS['error_msg'] = "只能上传图片";
  return;
}
if ($_FILES['images']['size'][$i] > 1 * 1024 *1024) {
  $GLOBALS['error_type'] = 'images';
  $GLOBALS['error_msg'] = "上传文件大小不合理";
  return;
}
$img_tmp_path = $_FILES['images']['tmp_name'][$i];
$img_dest_path = './uploads/img/' . $_FILES['images']['name'][$i];
$img_moved = move_uploaded_file($img_tmp_path,$img_dest_path);
if (!$img_moved) {
   $GLOBALS['error_type'] = 'images';
      $GLOBALS['error_msg'] = "上传图片失败";
      return;
}

$images [] = '/code5' . substr($img_dest_path, 1) ;

}

$new_song = array(
  'id' => uniqid(),
  'title' => $_POST['title'],
  'artist' => $_POST['artist'],
  'images' => $images,
  'source' => $source
);

$songs = json_decode(file_get_contents('data.json'),true);
$songs [] = $new_song;

file_put_contents('data.json', json_encode($songs));
header('Location: /code5/list.php');

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  postback();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>添加新音乐</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
  <div class="container py-5">
    <h1 class="display-3">添加新音乐</h1>
    <hr>
    <!--enctype="multipart/form-data"  只要是文件域必须带有这个属性 -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="title">标题</label>
        <input type="text" class="form-control <?php echo isset($error_type) && $error_type === 'title' ? 'is-invalid' : '';?>" id="title" name="title" value="<?php echo isset($_POST['title']) ? $_POST['title'] : '';?>">
        <small class="invalid-feedback"><?php echo $error_msg ?></small>
      </div>
      <div class="form-group">
        <label for="artist">歌手</label>
        <input type="text" class="form-control <?php  echo isset($error_type) && $error_type === 'artist' ? 'is-invalid' : '';?>" id="artist" name="artist" value="<?php echo isset($_POST['artist']) ? $_POST['artist'] : '';?>" >
        <small class="invalid-feedback"><?php echo $error_msg; ?></small>
      </div>
      <div class="form-group">
        <label for="images">海报</label>
        <!-- multiple 可以让文件域多选 -->
        <!-- accept 相当于在客户端进行限制 可以指定文件域能够选择的默认文件类型 MIME Type -->
        <!-- image/* 代表所有类型图片 -->
        <!-- 除了使用 MIME 类型 还可以使用文件后缀名限制：.png,.jpg -->
        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
      </div>
      <div class="form-group">
        <label for="source">音乐</label>
        <input type="file" class="form-control <?php echo isset($error_type) && $error_type === 'source' ? 'is-invalid' : '';?>"  id="source" name="source" accept="audio/*">
         <small class="invalid-feedback"><?php echo $error_msg ?></small>
      </div>
      <button class="btn btn-primary btn-block">保存</button>
    </form>
  </div>
</body>
</html>

