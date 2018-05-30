<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Upload</title>
</head>
<body>

<form method="post" role="form" action="./" enctype="multipart/form-data">
    <div class="form-group">
    	<p>每日代发下载</p>
        <input type="file" name="file" accept=".csv">
        <button type="submit" class="btn btn-default">Submit</button>
    </div>
    <div><p>出入库下载</p><a href="http://192.168.8.59/download/">download</a></div>
    
</form>
</body>
</html>

<?php
if($_FILES){
	//上传
	$upload  = $_FILES['file'];
	$file_name = $upload['tmp_name'];
	$handle = fopen($file_name, 'r');

	if($handle === FALSE) die('读取失败');

	while ($data = fgetcsv($handle))
		$list[] = $data[0];

	fclose($handle);

	//查询
	array_shift($list);
	$str = join(",",$list);
	$ids = "'".str_replace(",","','",$str)."'";

	$mysqli = mysqli_connect('数据库地址', '用户名', '密码', '数据库');
	$sql = "SELECT a.id ,a.sku ,c.item_count ,d.daifa_item_price ,(d.daifa_item_price/C.item_count) 单价 FROM warehouseorders a 
			LEFT JOIN deliveryorders b ON b.id = a.deliveryorders_id 
			LEFT JOIN erp_order_products c ON c.id = b.orders_products_id
			LEFT JOIN erp_order_products_daifa_detail d ON d.erp_order_product_id = c.id
			WHERE a.id IN( {$ids} )";

	$res = mysqli_query($mysqli, $sql);
	if(mysqli_num_rows($res) == 0) 
	die('没有数据');

	$rows = array();
	while($row = mysqli_fetch_assoc($res)) {
		$row['price'] = $row['daifa_item_price'] / $row['item_count'];
		$rows[] = $row;
	}

	//下载
	ob_clean();
	set_time_limit(0);  
	ini_set('memory_limit', '512M');  
	$output = fopen('php://output', 'w') or die("can't open php://output");  
	$filename = $upload['name'];  
	header("Content-Type: application/csv");  
	header("Content-Disposition: attachment; filename=$filename.csv");  
	$table_head = array('入库单号','SKU','数量', '总价格', '单价');  
	fputcsv($output, $table_head);  
	foreach ($rows as $e) {  
	fputcsv($output, array_values($e));  
	}  

	fclose($output) or die("can't close php://output");
 }