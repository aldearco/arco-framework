@title->(CSRF)
    @content
        <div class="d-flex justify-content-center align-items-center pt-4">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="display-6">Login</h1>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="text">Email</label>
                                <input name="file" type="file" value="<?= old("file") ?>" class="form-control">
                                <div class="text-danger"><?= error("file") ?></div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcontent