<?php
echo '
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
		<link rel="icon" type="image/svg+xml" href="/public/favicon.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Index</title>
		<link rel="stylesheet" href="/public/index.619048c6.css">
  </head>
  <body>
	';
	?>

Hello <?= htmlspecialchars(isset($name) ? $name : 'World', ENT_QUOTES, 'UTF-8'); ?>


<?php

echo '
	</body>
</html>';