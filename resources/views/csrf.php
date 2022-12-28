@title->(CSRF)

    @content
        <div class="d-flex justify-content-center align-items-center pt-4">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="display-6">Login</h1>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="text">Text</label>
                                <input name="text" type="text" value="<?= old("text") ?>" class="form-control">
                                <div class="text-danger"><?= error("text") ?></div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcontent