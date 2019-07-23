<?php
session_start([
    'cookie_lifetime' => 500,
]);

setcookie('tomato', 'catalog', time() + 60);
setcookie('tomato-catalog', 'catalog', time() + 60);

if (!isset($_SESSION['tomato'])) {
    $_SESSION['tomato'] = ['tomato', 'potato'][rand(0, 1)];
}

if (strpos($_SERVER['REQUEST_URI'], '/fail') === 0) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal server error');
    header('Link: </catalog/styles/error.css>; rel=stylesheet; type=text/css');
    echo '<div class="error">I failed miserably</div>';
    die;
}

if (strpos($_SERVER['REQUEST_URI'], '/not-found') === 0) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not found');
    echo 'Nothing to do here';
    die;
}

if (strpos($_SERVER['REQUEST_URI'], '/bad-request') === 0) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
    echo 'Your request does not look good<br/>' . PHP_EOL;
    die;
}
header('X-Custom-Test: Potato');
//header('Cache-Control: s-maxage=60');
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="/catalog/styles/main.css" />
    </head>
    <body>
        <section id="catalog-list">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                	<div class="catalog-item panel panel-primary" id="catalog-item-1">
                		<div class="panel-body">
                			<img src="/catalog/images/book.jpg" style="max-height: 512px" />
                		</div>
                		<p class="panel-footer description">This is the first item</p>
                	</div>
                </div>
            	<div class="col-sm-4">
                	<div class="catalog-item panel panel-primary" id="catalog-item-1">
                		<div class="panel-body">
                			<img src="/catalog/images/book.jpg" style="max-height: 512px" />
                		</div>
                		<p class="panel-footer description">This is the first <?php echo $_SESSION['tomato']; ?>  item</p>
                	</div>
                </div>
            	<div class="col-sm-4">
                	<div class="catalog-item panel panel-primary" id="catalog-item-1">
                		<div class="panel-body">
                			<img src="/catalog/images/book.jpg" style="max-height: 512px" />
                		</div>
                		<p class="panel-footer description">This is the first item</p>
                	</div>
                </div>
           	</div>
        </div>
        <div style="clear:both"></div>
        </section>

        <section id="catalog-search">
        	<form method="post">
        		<label for="catalog-search-field">Search in the catalog:</label>
        		<input type="text" id="catalog-search-field" name="search_term" />
        	</form>
        </section>
    </body>
</html>