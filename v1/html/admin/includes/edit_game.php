<?php 
if (isset($noajax)) {
	$id = $_GET['id'];
	$q = mysql_query("SELECT * FROM ava_games WHERE id=$id");
	$r = mysql_fetch_array($q);
}
else {
	require_once '../../config.php';
	include '../secure.php';
	if ($login_status != 1) exit();
	include ('../../includes/core.php');
	$id = $_POST['id'];
	$q = mysql_query("SELECT * FROM ava_games WHERE id=$id");
	$r = mysql_fetch_array($q);
}
$gamename = htmlspecialchars($r['name']);
$tags = TagList($r['id'], ", ", 0);
?>
<div class="form_container">
<br />
<form id="form1" name="form1" method="post" action="includes/edit_game_submit.php">
<div class="form_element_container">
   <div class="form_lable">
   <label>Game name</label></div>
   <div class="form_element"><input class="text_box" name="game_name" type="text" id="game_name<?php echo $r['id'];?>" value="<?php echo $gamename;?>" /></div>
</div>

<div class="form_element_container">
   <div class="form_lable"><label>Description</label></div>
   <div class="form_element"><textarea class="text_area" name="game_description" id="game_description<?php echo $r['id'];?>"><?php echo $r['description'];?></textarea></div>
</div>

<div class="form_element_container">
   <div class="form_lable"><label>How to play</label></div>
   <div class="form_element"><textarea class="text_area" name="game_instructions" id="game_instructions<?php echo $r['id'];?>"><?php echo $r['instructions'];?></textarea></div>
</div>

<div class="form_element_container">
   <div class="form_lable">
   <label>Tags</label></div>
   <div class="form_element"><input class="text_box" name="game_tags" type="text" id="game_tags<?php echo $r['id'];?>" value="<?php echo $tags;?>"/></div>
</div>

<?php
$eub = '';
$codeb = '';
if ($r['filetype'] != 'code') {
	$eub = 'class="bold"';
}
else {
	$codeb = 'class="bold"';	
}
?>

<div class="form_element_container">
   <div class="form_lable"><label>File</label></div>
   <div class="form_element">
      <a href="#" onclick="file_selector(3, 0, <?php echo $r['id'];?>);return false" id="enter_url_link<?php echo $r['id'];?>" <?php echo $eub;?>>Enter URL</a> | <a href="#" onclick="file_selector(2, 0, <?php echo $r['id'];?>);return false" id="select_link<?php echo $r['id'];?>">Select from games folder</a> | <a href="#" onclick="file_selector(1, 0, <?php echo $r['id'];?>);return false" id="upload_link<?php echo $r['id'];?>">Upload file</a> | <a href="#" onclick="file_selector(4, 0, <?php echo $r['id'];?>);return false" id="grab_link<?php echo $r['id'];?>">Grab file</a> | <a href="#" onclick="file_selector(5, 0, <?php echo $r['id'];?>);return false" id="file_html_code<?php echo $r['id'];?>" <?php echo $codeb;?>>HTML</a>
      
      <div id="file_selection<?php echo $r['id'];?>">
      	<?php if ($r['filetype'] != 'code') { ?>
      	<input name="url" type="text" class="text_box" value="<?php echo $r['url'];?>" id="url<?php echo $r['id'];?>" />
      	<?php } else { ?>
      	<textarea class="text_area_code" name="html_code" id="html_code<?php echo $r['id'];?>"><?php echo $r['html_code'];?></textarea><br />
      	<div class="html_options">Optional: <a href="#" onclick="htmlToGame(<?php echo $r['id'];?>);return false" id="html_link<?php echo $r['id'];?>">Get details from HTML</a> or just submit plain HTML to use that.</div>
      	<?php } ?>
     </div>
   </div>
</div>

<div class="form_element_container">
   <div class="form_lable"><label>Image</label></div>
   <div class="form_element">
      <a href="#" onclick="image_selector(3, 0, <?php echo $r['id'];?>);return false" id="enter_url_link_image<?php echo $r['id'];?>" class="bold">Enter URL</a> | <a href="#" onclick="image_selector(2, 0, <?php echo $r['id'];?>);return false" id="select_link_image<?php echo $r['id'];?>">Select from images folder</a> | <a href="#" onclick="image_selector(1, 0, <?php echo $r['id'];?>);return false" id="upload_link_image<?php echo $r['id'];?>">Upload image</a> | <a href="#" onclick="image_selector(4, 0, <?php echo $r['id'];?>);return false" id="grab_link_image<?php echo $r['id'];?>">Grab image</a>
      <div id="image_selection<?php echo $r['id'];?>"><input name="url" type="text" class="text_box" value="<?php echo $r['image'];?>" id="img<?php echo $r['id'];?>" /></div>
   </div>
</div>

<div class="form_element_container">
   <div class="form_lable"><label>Dimensions</label></div>
   <div class="form_element"><input name="width" type="text" class="text_box_dimensions" id="width<?php echo $r['id'];?>" value="<?php echo $r['width'];?>" size="3" /> x <input name="height" type="text" class="text_box_dimensions" id="height<?php echo $r['id'];?>" value="<?php echo $r['height'];?>" size="3" /> (<a href="#" onclick="GetDimensions(<?php echo $r['id'];?>);return false">Auto</a>)
   </div></div>

<div class="form_element_container">
   <div class="form_lable"><label>Category</label></div>
   <div class="form_element"><select name="category" id="category<?php echo $r['id'];?>">
   <?php categorylist($r['category_id']); ?>
   </select></div>
</div>


<div class="form_element_container">
   <div class="form_lable"><label>Advert</label></div>
   <div class="form_element"><select name="advert" id="advert<?php echo $r['id'];?>">
   <?php $cq = mysql_query("SELECT * FROM ava_adverts ORDER BY ad_name ASC");
   if ($r['advert_id'] == 0)
   	    echo '<option value="0" selected>None</option>';
   else
   		echo '<option value="0">None</option>';
   		
   if ($r['advert_id'] == 1)
   		echo '<option value="1" selected>Sitewide default</option>';
   else 
   		echo '<option value="1">Sitewide default</option>';
   while($ca = mysql_fetch_array($cq)) {
   		if ($ca['id'] != 1)	{
			if ($ca['id'] == $r['advert_id'])
				echo '<option value="'.$ca['id'].'" selected>'.$ca['ad_name'].'</option>'; 
			else 
		   		echo '<option value="'.$ca['id'].'">'.$ca['ad_name'].'</option>'; 
		}
   }?>
   </select></div>
</div>

<div class="form_element_container">
   <div class="form_lable"><label>Highscores</label></div>
   <div class="form_element">
   <?php if ($r['highscores'] == 1) {
   		echo '<input type="checkbox" name="highscores" value="1" id="highscores'.$r['id'].'" checked="checked">';
   	}
   	else {
   		echo '<input type="checkbox" name="highscores" value="1" id="highscores'.$r['id'].'">';
   	}
   	?>
   </div>
</div>

<div class="form_element_container">
   <div class="form_lable">
   <label>Mochi game tag</label></div>
   <div class="form_element"><input class="text_box" name="mochi_id" type="text" id="mochi_id<?php echo $r['id'];?>" value="<?php echo $r['mochi_id'];?>"/></div>
</div>

<div class="form_element_container">
   <div class="form_lable"><label>Published</label></div>
   <div class="form_element">
   <?php if ($r['published'] == 1) {
   		echo '<input type="checkbox" name="published" value="1" id="published'.$r['id'].'" checked="checked">';
   	}
   	else {
   		echo '<input type="checkbox" name="published" value="1" id="published'.$r['id'].'">';
   	}
   	?>
   </div>
</div>

<div class="form_element_container">
   <div class="form_lable">
   <label>Submitter ID</label></div>
   <div class="form_element"><input class="text_box_id" name="submitter" type="text" id="submitter<?php echo $r['id'];?>" value="<?php echo $r['submitter'];?>" /></div>
</div>

<input name="id" type="hidden" value="<?php echo $r['id'];?>" id="id<?php echo $r['id'];?>" />
<div class="button_container"><input class="button" name="Submit" type="button" value="Submit" id="submit<?php echo $r['id'];?>" onclick="SubmitGame('<?php echo $r['id'];?>');" /></div>
</form>
</div>