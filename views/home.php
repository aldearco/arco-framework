<h1>Home</h1>
<h2>Hello, <?php echo $user ?></h2>
<?php 
foreach (["Mensaje 1", "Mensaje 2"] as $message) {
    echo "<p>{$message}</p>";
}
?>