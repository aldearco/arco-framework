#title->(Test varios)

#content
<div class="d-flex justify-content-center align-items-center pt-4">
  <div class="col-lg-4 col-md-6 col-12">
    <div class="card">
      <div class="card-header">
        <h1 class="display-6">Test paginacion</h1>
      </div>
      <div class="card-body">
        <pre>
          <?php foreach ($users as $user) {
            var_dump($user->toArray());
          } ?>
        </pre>
        <?php pagination()->links() ?>
      </div>
    </div>
  </div>
</div>
#endcontent