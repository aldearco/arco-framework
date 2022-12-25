<h1>Create Contact</h1>
<form method="POST" action="/contacts">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" type="text" value="<?= old("name") ?>" class="form-control">
        <div class="text-danger"><?= error("name") ?></div>
    </div>
    <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input name="phone_number" type="text" value="<?= old("phone_number") ?>" class="form-control">
        <div class="text-danger"><?= error("phone_number") ?></div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>