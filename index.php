<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazione Barbiere</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function disableMondays() {
            const dateInput = document.querySelector('input[name="data_prenotazione"]');
            dateInput.addEventListener('change', () => {
                const day = new Date(dateInput.value).getDay();
                if (day === 1) {
                    alert("Non si accettano prenotazioni il lunedì.");
                    dateInput.value = "";
                }
            });
        }

        function generateTimeOptions() {
            const timeInput = document.querySelector('select[name="orario"]');
            for (let hour = 9; hour < 19; hour++) {
                for (let min of [0, 30]) {
                    let h = hour.toString().padStart(2, '0');
                    let m = min.toString().padStart(2, '0');
                    let option = document.createElement('option');
                    option.value = `${h}:${m}`;
                    option.textContent = `${h}:${m}`;
                    timeInput.appendChild(option);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            disableMondays();
            generateTimeOptions();
        });
    </script>
</head>
<body>
    <div class="form-container">
        <form action="prenota.php" method="POST">
            <h1>Prenota ora</h1>
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="Email">
            <input type="tel" name="telefono" placeholder="Telefono">
            <select name="servizio" required>
                <option value="">Seleziona Servizio</option>
                <?php
                include 'connessione.php';
                $result = $conn->query("SELECT nome FROM servizi");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['nome']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
            <input type="date" name="data_prenotazione" required>
            <select name="orario" required></select>
            <button type="submit">Prenota</button>
            <div class="admin-link"><a href="login.php">Area Admin</a></div>
        </form>
    </div>
</body>
</html>