<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
		<link rel="icon" type="image/svg+xml" href="/public/favicon.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Not found</title>
		<link rel="stylesheet" href="/public/index.619048c6.css">
  </head>
  <body>
		<h1 class="error-message">Sorry, we can't find this resource: <span class="failed-resource"> <?= $view['failedRoute'] ?> </span> in our service</h1>
		<p>
			<?php var_dump($exception); ?>
		</p>
  </body>
</html>
