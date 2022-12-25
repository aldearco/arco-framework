<h1>Edit Contact</h1>
<form method="POST" action="/contacts/edit/<?php echo $contact->id ?>">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" type="text" value="<?php echo old("name") ?? $contact->name ?>" class="form-control">
        <div class="text-danger"><?= error("name") ?></div>
    </div>
    <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input name="phone_number" type="text" value="<?php echo old("phone_number") ?? $contact->phone_number ?>" class="form-control">
        <div class="text-danger"><?= error("phone_number") ?></div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>