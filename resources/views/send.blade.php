@include('includes.header')
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

@include('includes.footer')
