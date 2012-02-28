<?php

	/* Made with love by Tyler, feel free to remove this. :D */

	mysql_connect('localhost', 'USERNAME', 'PASSWORD') or die(mysql_error());
	mysql_select_db('tutorials');

	$ip = $_SERVER['REMOTE_ADDR'];

	$avatar_config = array(
		'width' => 120,
		'height' => 150
	);

	if( ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)){
		die('Invalid IP Address!');
	}

	// Let's get the list of items!
	$avatar_items = mysql_query("SELECT * FROM avatar_items JOIN avatar_layers ON avatar_items.layer_id = avatar_layers.layer_id");

	// Let's get what items the user has equipped!
	$avatar_data = mysql_fetch_assoc(mysql_query("SELECT * FROM avatars WHERE ip_address = '".$ip."'"));

	if( ! $avatar_data){
		// Create a new avatar user!
		mysql_query("INSERT INTO `avatars` (id, ip_address, avatar_data) VALUES (NULL, '".$ip."', '".json_encode(array())."')") or die(mysql_error());
		$equipped_items = array();
	} else {
		$equipped_items = json_decode($avatar_data['avatar_data'], TRUE);
	}

	function make_transparent($image_obj = array())
	{
		$transcol = imagecolorallocatealpha($image_obj, 255, 0, 255, 127);
		$trans = imagecolortransparent($image_obj, $transcol);
		imagefill($image_obj, 0, 0, $transcol);
		return $image_obj;
	}

	if(isset($_GET['action'])){
		switch($_GET['action']){
			case "show_avatar":
				$avatar_canvas = make_transparent(imagecreatetruecolor($avatar_config['width'], $avatar_config['height'])); // create the canvas
				$building_items = array();

				while($item_data = mysql_fetch_assoc($avatar_items)){
					if($item_data['permanent'] == 1){
						$building_items[] = $item_data['item_id'];
					}
				}

				$building_items = array_merge(array_keys($equipped_items), $building_items);

				$avatar_images = mysql_query("SELECT image_path FROM avatar_items JOIN avatar_layers ON avatar_items.layer_id = avatar_layers.layer_id WHERE item_id IN (".implode($building_items, ',').") ORDER BY avatar_layers.layer_order ASC") or die(mysql_error());

				while($item = mysql_fetch_assoc($avatar_images)){
					$new_image = imagecreatefrompng('images/'.$item['image_path']);
					imagecopy($avatar_canvas, $new_image, 0, 0, 0, 0, $avatar_config['width'], $avatar_config['height']);
				}

				// Return the image!
				header('Content-type: image/png');
				imagepng($avatar_canvas);

				// No need to "remember" the image. Let's get rid of it.
				imagedestroy($avatar_canvas);
			break;
			case "equip_item":
				if(isset($equipped_items[$_GET['item_id']])){
					unset($equipped_items[$_GET['item_id']]); // Unequip!
				} else {
					$equipped_items[$_GET['item_id']] = TRUE;  // Equip!
				}

				mysql_query("UPDATE avatars SET avatar_data = '".json_encode($equipped_items)."' WHERE ip_address = '".$ip."'") or die(mysql_error());
				header('Location: index.php'); // Redirect them to the index!
			break;
		}
	} elseif(isset($_POST['save_avatar'])){
		// Save the avatar!
		$avatar_canvas = make_transparent(imagecreatetruecolor($avatar_config['width'], $avatar_config['height'])); // create the canvas
		$building_items = array();

		while($item_data = mysql_fetch_assoc($avatar_items)){
			if($item_data['permanent'] == 1){
				$building_items[] = $item_data['item_id'];
			}
		}

		$building_items = array_merge(array_keys($equipped_items), $building_items);

		$avatar_images = mysql_query("SELECT image_path FROM avatar_items JOIN avatar_layers ON avatar_items.layer_id = avatar_layers.layer_id WHERE item_id IN (".implode($building_items, ',').") ORDER BY avatar_layers.layer_order ASC") or die(mysql_error());

		while($item = mysql_fetch_assoc($avatar_images)){
			$new_image = imagecreatefrompng('images/'.$item['image_path']);
			imagecopy($avatar_canvas, $new_image, 0, 0, 0, 0, $avatar_config['width'], $avatar_config['height']);
		}

		// Return the image!
		imagepng($avatar_canvas, 'images/saved_avatars/'.md5($ip).'.png');

		header('Location: images/saved_avatars/'.md5($ip).'.png');
	} else {
		require_once('views/avatar.php');
	}
	
?>