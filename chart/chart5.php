<?php
$pdo = new pdo("mysql:host=localhost;dbname=iwebshop","root","root");
$sql = ("select name,sell_price from iwebshop_goods where sell_price>6000");
$data = $pdo->query ($sql) ->fetchAll();
print_r($data);