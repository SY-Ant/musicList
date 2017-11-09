<?php 

$json = file_get_contents('data.json');

$db_json = json_decode($json,true);



 ?>


<!DOCTYPE <html></html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>音乐列表</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
  <div class="container py-5">
    <h1 class="display-3">音乐列表</h1>
    <hr>
    <div class="px-2 mb-3">
      <a href="/code5/add.php" class="btn btn-secondary btn-sm">添加</a>
    </div>
    <table class="table table-bordered table-striped table-hover">
      <thead class="thead-inverse">
        <tr>
        	<th class="text-center">添加</th>
          <th class="text-center">标题</th>
          <th class="text-center">歌手</th>
          <th class="text-center">海报</th>
          <th class="text-center">音乐</th>
          <th class="text-center">操作</th>
        </tr>
      </thead>
      <tbody class="text-center"> 
        <?php foreach ($db_json as $item): ?>
          <tr>
            <td class="text-center"><input type="checkbox" name="" id=""></td>
            <td class="align-middle"><?php echo $item['title'] ?></td>
            <td class="align-middle"><?php echo $item['artist'] ?></td>
            <td class="align-middle">
            <?php foreach ($item['images'] as $img): ?>
              <img src="<?php echo $img; ?>"></td>
            <?php endforeach ?>
            <td class="align-middle"><audio src="<?php echo $item['source']; ?>" controls></audio></td>
            <td class="align-middle">
            <a class="btn btn-outline-danger btn-sm" href="/code5/del.php?id=<?php echo $item['id']; ?>">删除</a>
            </td>
          </tr>
        <?php endforeach ?>
       
      </tbody>
    </table>
  </div>
</body>
</html>
