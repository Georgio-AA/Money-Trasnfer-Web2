<?php echo $__env->make('includes.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<h2>Send Money</h2>
<form method="POST" action="">
    <label>Recipient Name:</label>
    <input type="text" name="recipient" required>

    <label>Country:</label>
    <input type="text" name="country" required>

    <label>Amount (USD):</label>
    <input type="number" name="amount" required>

    <label>Payment Method:</label>
    <select name="method">
        <option>Bank Transfer</option>
        <option>Credit Card</option>
    </select>

    <button type="submit" name="send">Send Money</button>
</form>

<?php
if (isset($_POST['send'])) {
    $recipient = htmlspecialchars($_POST['recipient']);
    $country = htmlspecialchars($_POST['country']);
    $amount = (float)$_POST['amount'];
    echo "<p class='success'>Money transfer to $recipient ($country) of \$$amount initiated successfully!</p>";
}
?>

<?php echo $__env->make('includes.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\money-transfer\WebProject\resources\views/send.blade.php ENDPATH**/ ?>