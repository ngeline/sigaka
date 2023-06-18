<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $title ?></title>
	<style>
		body {
			font-family: Arial, sans-serif;
		}

		h1 {
			color: #555;
		}

		.content {
			margin-top: 20px;
			padding: 10px;
			background-color: #f9f9f9;
		}
	</style>
</head>

<body>
	<h1><?= $title ?></h1>
	<div class="content">
		<?= $content ?>
	</div>
</body>

</html>