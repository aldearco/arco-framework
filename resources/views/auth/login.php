<h1>Login</h1>
<form method="post">
  <div class="mb-3">
    <label class="email">Email</label>
    <input name="email" type="text" value="<?= old("email") ?>" class="form-control">
    <div class="text-danger"><?= error("email") ?></div>
  </div>

  <div class="mb-3">
    <label class="password">Password</label>
    <input name="password" type="password" class="form-control">
    <div class="text-danger"><?= error("password") ?></div>
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
