<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	$id = (int)$data['id'];

	$deep = subGroupGetDeep_new($id);
	$deep = $deep ? $deep : 1;
	if(!$id)$where = "IS NULL";
	else {$where = "= '".$id."'"; $deep++;}

	$request = $bd->read("SELECT * FROM `productgroup` WHERE `subgroup` ".$where." ORDER BY `name`");

	$return = '';

	for($i=0;$i<$bd->rows;$i++){
		$idList = mysql_result($request,$i,0);
		$name = strBaseOut(mysql_result($request,$i,2));
		$return .= <<<HTML
		<div class="admin_cat_punct">
			<div class="btn btn-info btn-sm" onclick="panel.admin.group.list({$idList});" data-admin_cat_btn="{$idList}">{$name}</div>
			<span class="span_clear" onclick="panel.admin.group.del({$idList}, this);">×</span>
		</div>
HTML;

	}

	$return = <<<HTML
<div class="admin_cat_form">
	<div class="admin_cat_group">{$return}</div>
	<form style="margin-bottom: 10px;" class="form_content_def" onsubmit="panel.admin.group.create(this, {$id});return false;">
		<input style="width: 150px;" type="text" maxlength="50" name="name">
		<button class="btn btn-success btn-sm" name="button">Создать</button>
	</form>
</div>
HTML;

	close(json_encode(array('status' => 'ok', 'content' => $return, 'deep' => $deep)));
?>