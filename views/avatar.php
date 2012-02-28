<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>Customize your avatar!</title>
		<style type="text/css" media="screen">
			* { margin:0; padding:0 } 
			body { background:#f5f5f5; color:#222; font-family:Arial, sans-serif; }
			#main_content {
				background:white;
				width:400px;
				padding:30px 40px;
				height:300px;
				margin:0 auto;
				border:1px solid #ddd;
				border-top:none;
				text-align:center;
			}
		</style>
	</head>
	<body>
		<div id="main_content">
			<h2>Create and customize your avatar</h2>
			<img src="index.php?action=show_avatar" alt="" />

			<form action="" method="POST">
				<input type="hidden" name="save_avatar" value="true" />
				<button type="submit">Save avatar</button>
			</form>
			
			<br />
			<?php while($item_data = mysql_fetch_assoc($avatar_items)): ?>
				<?php if ($item_data['permanent'] == 0): ?>
					<a href="index.php?action=equip_item&item_id=<?php echo $item_data['item_id'] ?>"><img src="images/thumbnails/<?php echo $item_data['thumbnail'] ?>" /></a>
				<?php endif ?>
			<? endwhile; ?>
		</div>
	</body>
</html>