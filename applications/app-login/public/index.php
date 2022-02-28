<?php
session_start([
    'cookie_lifetime' => 86400,
]);
setcookie('tomato', 'login', time() + 60);
setcookie('tomato-login', 'login', time() + 60);

if ($_SERVER['REQUEST_URI'] === '/logout') {
    session_destroy();
    header('X-tomato: potato');
    header('Location: /');
    die;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === 'user' && $_POST['password'] === 'user') {
        $_SESSION['user'] = ['name' => 'Potato'];
    }
}
?><html>
<body>
<section id="login-header">
    <section id="analytics"></section>
    <?php if (isset($_SESSION['user'])) : ?>
    <ul class="nav navbar-nav navbar-right">
	    <li class="nav-item"><a class="nav-link" href="#"><span class="glyphicon glyphicon-user"></span> Hello <?= $_SESSION['user']['name']; ?>, Your Account</a></li>
        <li class="nav-item"><a class="nav-link"  href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Cart</a></li>
        <li class="nav-item"><a class="nav-link"  href="/logout"><span class="glyphicon glyphicon-shopping-cart"></span> Logout</a></li>
   	</ul>
    <?php else: ?>
    	<section id="login-form">
        	<form method="post" action="/" class="form-inline mt-2 mt-md-0">
        		<input class="form-control mr-sm-2" placeholder="Username"
        			type="text" id="login-username" name="username" />
        		<input  class="form-control mr-sm-2" placeholder="Password"
        			type="password" id="login-password" name="password" />
        		<input class="btn btn-outline-success my-2 my-sm-0"
        			type="submit" value="Login" />
        	</form>
    	</section>
    <?php endif; ?>
    </section>

    <script type="text/javascript">
        console.log('it works');
    </script>
</body>
</html>