@title->(Login)

  @content
    <div class="d-flex justify-content-center align-items-center pt-4">
      <div class="col-lg-4 col-md-6 col-12">
        <div class="card">
          <div class="card-header">
            <h1 class="display-6">Login</h1>
          </div>
          <div class="card-body">
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
          </div>
        </div>
      </div>
    </div>
  @endcontent