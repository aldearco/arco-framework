#title->(Test rules)

#content
<div class="d-flex justify-content-center align-items-center pt-4">
  <div class="col-lg-4 col-md-6 col-12">
    <div class="card">
      <div class="card-header">
        <h1 class="display-6">Test varios</h1>
      </div>
      <div class="card-body">
        <form method="post" action="<?= route('rules.store') ?>">
          #csrf
          <div class="mb-3">
            <label class="rule"><?= __('Rules tests') ?></label>
            <input name="rule" type="rule" class="form-control">
            <div class="text-danger"><?= error("rule") ?></div>
          </div>
          <button type="submit" class="btn btn-primary"><?= __('Submit') ?></button>
        </form>
      </div>
    </div>
  </div>
</div>
#endcontent