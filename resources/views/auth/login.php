#title->(<?= __('login.title') ?>)

#content
<div class="d-flex justify-content-center align-items-center pt-4">
  <div class="col-lg-4 col-md-6 col-12">
    <div class="card">
      <div class="card-header">
        <h1 class="display-6"><?= __('login.title') ?></h1>
      </div>
      <div class="card-body">
        <form method="post">
          #csrf
          <div class="mb-3">
            <label class="email"><?= __('login.email') ?></label>
            <input name="email" type="text" value="<?= old("email") ?>" class="form-control">
            <div class="text-danger"><?= error("email") ?></div>
          </div>

          <div class="mb-3">
            <label class="password"><?= __('login.password') ?></label>
            <input name="password" type="password" class="form-control">
            <div class="text-danger"><?= error("password") ?></div>
          </div>

          <div class="mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php old('remember') ? 'checked' : '' ?>>
            <label class="form-check-label" for="remember">
              <?= __('login.remember') ?>
            </label>
          </div>
          <button type="submit" class="btn btn-primary"><?= __('Submit') ?></button>
        </form>
      </div>
    </div>
  </div>
</div>
#endcontent