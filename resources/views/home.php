@title->(Soy el título de la <?= "test" ?>)

    @content
        <h1>Welcome</h1>
        <?php if (isGuest()): ?>
        <div>
            <a href="/login">Login</a>
        </div>
        <div>
            <a href="/register">Register</a>
        </div>
        <?php else: ?>
        You are logged in!
        <?php endif; ?>
    @endcontent