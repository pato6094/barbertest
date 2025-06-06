<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header("Location: login.php");
    exit();
}
include 'connessione.php';
?>
<!DOCTYPE html>
<html>
<head><title>Admin</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="form-container">
    <h1>Gestione Prenotazioni</h1>
    <form method="POST" action="aggiungi_servizio.php">
        <input type="text" name="nome_servizio" placeholder="Nuovo Servizio" required>
        <input type="text" name="prezzo" placeholder="Prezzo" required>
        <button type="submit">Aggiungi Servizio</button>
    </form>
    <form method="POST" action="svuota.php" style="margin-top:15px;">
        <button type="submit" onclick="return confirm('Sicuro di cancellare tutte le prenotazioni?')">Svuota Prenotazioni</button>
    </form>
    <h2>Elenco Prenotazioni</h2>
    <table><tr><th>Data</th><th>Ora</th><th>Nome</th><th>Servizio</th></tr>
<?php
$result = $conn->query("SELECT * FROM prenotazioni ORDER BY data_prenotazione, orario");
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['data_prenotazione']}</td><td>{$row['orario']}</td><td>{$row['nome']}</td><td>{$row['servizio']}</td></tr>";
}
?>
</table>
</div>
</body>
</html>