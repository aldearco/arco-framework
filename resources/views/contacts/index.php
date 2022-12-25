<h1>Contacts</h1>

<?php foreach ($contacts as $contact): ?>
<div>
    <?php echo $contact->name ?>
    <?php echo $contact->phone_number ?>
</div>
<?php endforeach; ?>