<?php
$pdo=new PDO('mysql:host=localhost;dbname=iwebshop;charset=utf8','root','root');
$sql="select sell_price from iwebshop_goods where sell_price>5000";
$data=$pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre/>';
// print_r($data);

// 下面使用两种方法从二维数组中取出某值，将其构造成一维数组，以便在highcharts中使用

// 方法一：
// 语法 $arr=array_column(二维数组,二维数组中包含的一维数组的某个键的键名);
$arr=array_column($data,'sell_price');
// echo '<pre/>';
// print_r($arr);
$str=join($arr,',');		
// echo $str;
// die;

// 方法二：
// 1 二维数组转换成一维数组
foreach ($data as $key => $value) {
	$arr[]=$value['sell_price'];
}
// echo '<pre/>';
// print_r($arr);
// 2 将一维数组转换成字符串，各个值之间用逗号分隔 x,y,z,a,b
$str=implode($arr,',');		
// echo $str;
// die;
?>
<html>
<head>
	<meta charset='utf8'>
	<title></title>
	<!-- 1 引入highCharts 图表js文件（源码） -->
	<script src="highcharts.js"></script>
</head>
<body>
	<!-- 2 容纳图表的元素 -->
	<div id="container" style="width: 600px;height:400px;"></div>
</body>
</html>

<!-- 在下面的js脚本中设置图表参数、创建图表 -->
<script>
	// 图表参数
	var options={
		// 图表类型参数
		chart:{type:'column'},
		// 图表主标题
		title:{text:'我的第一个图表'},
		// X轴分类标题
		xAxis:{categories:['苹果','香蕉','橙子']},
		// Y轴标题
		yAxis:{
			title:{text:'水果个数'}
		},
		// 图表展示的数据
		series:[
			// 第1个数据系列
			{
				name:'小明',
				data:[<?php echo $str;?>]
			},
			// 第2个数据系列
			{
				name:'小红',
				data:[5,7,3]
			}
		],
	};

	// 创建图表（把图表展示到id是container的div元素中）
	// 语法 highcharts.chart(容器id,图表配置参数)
	var chart=Highcharts.chart("container", options);
</script>