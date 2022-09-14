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
		<h1 class="error-message">You asked for <span class="failed-resource"> <?= $view['failedRoute'] ?> </span> but...</h1>
		<p>
			Error 404: <?= $exception ?>
		</p>
  </body>
</html>
