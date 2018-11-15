<?php
$pdo=new PDO('mysql:host=localhost;dbname=iwebshop','root','root');
$data=$pdo->query('select name,sell_price from iwebshop_goods where id<10')->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre/>';
// print_r($data);
foreach ($data as $key => $value) {
	//$name=mb_substr($value['name'],0,2);
	$name=$value['name'];
	$sell_price=$value['sell_price'];
	$d1[]="['$name',$sell_price]";
}
// echo '<pre/>';
// print_r($d1);
$s1=join($d1,',');
// echo $s1;die;
?>
<html>
<head>
	<title></title>
	<!-- 引用highcharts图表的js文件 -->
	<script src="highcharts.js"></script>
</head>
<body>
	<!-- 展示图表的容器 -->
	<div id="container" style="width: 600px;height:400px;"></div>
</body>
</html>
<!-- 图表配置 -->
<script>
	var options={
		chart:{type:'spline'},
		title:{text:'商品销售'},
		//xAxis:{categories:['北京','上海','天津','深圳','八维']},
		yAxis:{title:{text:'价格'}},
		// 设置数组系列（具体要展示的数据—）
		series:[
		{data:[<?php echo $s1;?>]},
		]
	};
	// 初始化图表 语法 var chart=highcharts.chart('容器ID',图表配置对象);
	var chart=Highcharts.chart('container',options);
</script>
