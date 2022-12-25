<h1>Contacts</h1>

<?php foreach ($contacts as $contact): ?>
<div>
    <?php echo $contact->name ?>
    <?php echo $contact->phone_number ?>
    <a href="/contacts/edit/<?php echo $contact->id ?>">Edit</a>
    <a href="/contacts/delete/<?php echo $contact->id ?>">Delete</a>
</div>
<?php endforeach; ?>