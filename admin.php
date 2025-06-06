<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header("Location: login.php");
    exit();
}
include 'connessione.php';

// Get statistics
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM prenotazioni")->fetch_assoc()['count'];
$today_bookings = $conn->query("SELECT COUNT(*) as count FROM prenotazioni WHERE data_prenotazione = CURDATE()")->fetch_assoc()['count'];
$pending_bookings = $conn->query("SELECT COUNT(*) as count FROM prenotazioni WHERE data_prenotazione >= CURDATE()")->fetch_assoc()['count'];
$total_services = $conn->query("SELECT COUNT(*) as count FROM servizi")->fetch_assoc()['count'];

// Get recent bookings
$recent_bookings = $conn->query("SELECT * FROM prenotazioni ORDER BY data_prenotazione DESC, orario DESC LIMIT 5");

// Get services for revenue calculation
$services_result = $conn->query("SELECT s.nome, s.prezzo, COUNT(p.id) as bookings FROM servizi s LEFT JOIN prenotazioni p ON s.nome = p.servizio GROUP BY s.nome, s.prezzo");
$total_revenue = 0;
$service_stats = [];
while ($row = $services_result->fetch_assoc()) {
    $revenue = floatval($row['prezzo']) * intval($row['bookings']);
    $total_revenue += $revenue;
    $service_stats[] = $row;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Barber Shop</title>
    <link rel="stylesheet" href="admin-style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-icon">BS</div>
            </div>
            <nav class="nav-menu">
                <a href="#dashboard" class="nav-item active" onclick="showSection('dashboard')">
                    <i class="fas fa-chart-pie"></i>
                </a>
                <a href="#reservations" class="nav-item" onclick="showSection('reservations')">
                    <i class="fas fa-calendar-alt"></i>
                </a>
                <a href="#services" class="nav-item" onclick="showSection('services')">
                    <i class="fas fa-cut"></i>
                </a>
                <a href="#customers" class="nav-item" onclick="showSection('customers')">
                    <i class="fas fa-users"></i>
                </a>
                <a href="#analytics" class="nav-item" onclick="showSection('analytics')">
                    <i class="fas fa-chart-line"></i>
                </a>
                <a href="#settings" class="nav-item" onclick="showSection('settings')">
                    <i class="fas fa-cog"></i>
                </a>
            </nav>
            <div class="logout-section">
                <a href="logout.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search here...">
                </div>
                <div class="header-right">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge"><?php echo $today_bookings; ?></span>
                    </div>
                    <div class="user-profile">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="user-name">Old school barber</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <!-- Dashboard Section -->
            <div id="dashboard" class="content-section active">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card confirmed">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Confirmed</h3>
                            <span class="stat-number"><?php echo $total_bookings; ?></span>
                        </div>
                    </div>
                    <div class="stat-card pending">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Pending</h3>
                            <span class="stat-number"><?php echo $pending_bookings; ?></span>
                        </div>
                    </div>
                    <div class="stat-card canceled">
                        <div class="stat-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Services</h3>
                            <span class="stat-number"><?php echo $total_services; ?></span>
                        </div>
                    </div>
                    <div class="stat-card deals">
                        <div class="deals-content">
                            <h3>Number of deals</h3>
                            <div class="deals-chart">
                                <div class="chart-circle">
                                    <span class="chart-number"><?php echo $total_bookings; ?></span>
                                </div>
                                <div class="chart-stats">
                                    <div class="stat-item">
                                        <span class="dot successful"></span>
                                        <span>70% Successful</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="dot unsuccessful"></span>
                                        <span>30% Unsuccessful</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Dashboard Content -->
                <div class="dashboard-grid">
                    <!-- Reservations Table -->
                    <div class="dashboard-card reservations-card">
                        <div class="card-header">
                            <h2>Reservation</h2>
                            <a href="#" class="view-all">View all</a>
                        </div>
                        <div class="service-tabs">
                            <button class="tab-btn active">Haircut</button>
                            <button class="tab-btn">Beard Trim</button>
                            <button class="tab-btn">Haircolor</button>
                            <button class="tab-btn">Razor Shave</button>
                            <button class="tab-btn">Highlights</button>
                        </div>
                        <div class="reservations-list">
                            <?php
                            $counter = 1;
                            while ($row = $recent_bookings->fetch_assoc()) {
                                $status_class = 'confirm';
                                $status_text = 'Confirm';
                                if (strtotime($row['data_prenotazione']) < strtotime('today')) {
                                    $status_class = 'completed';
                                    $status_text = 'Completed';
                                }
                                echo "<div class='reservation-item'>
                                        <div class='reservation-number'>{$counter}</div>
                                        <div class='reservation-name'>{$row['nome']}</div>
                                        <div class='reservation-time'>{$row['orario']}</div>
                                        <div class='reservation-status {$status_class}'>{$status_text}</div>
                                        <div class='reservation-barber'>Admin</div>
                                      </div>";
                                $counter++;
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Right Side Cards -->
                    <div class="right-cards">
                        <!-- Users Stats -->
                        <div class="dashboard-card users-card">
                            <h3>Total number of users</h3>
                            <div class="users-chart">
                                <div class="chart-circle-small">
                                    <span class="chart-number-small"><?php echo $total_bookings * 10; ?></span>
                                </div>
                                <div class="chart-stats-small">
                                    <div class="stat-item">
                                        <span class="dot successful"></span>
                                        <span>85% Successful</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="dot unsuccessful"></span>
                                        <span>15% Unsuccessful</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Styles -->
                        <div class="dashboard-card styles-card">
                            <div class="card-header">
                                <h3>Top Styles</h3>
                                <a href="#" class="see-all">See all</a>
                            </div>
                            <p class="styles-subtitle">See the most popular styles by Stylist</p>
                            <div class="styles-image">
                                <img src="https://images.pexels.com/photos/1319460/pexels-photo-1319460.jpeg?auto=compress&cs=tinysrgb&w=300&h=200&fit=crop" alt="Barber at work">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section -->
                <div class="bottom-section">
                    <!-- Top Visitors Chart -->
                    <div class="dashboard-card visitors-card">
                        <div class="card-header">
                            <h3>Top Visitors</h3>
                            <select class="time-filter">
                                <option>Last Week</option>
                                <option>Last Month</option>
                            </select>
                        </div>
                        <div class="chart-container">
                            <canvas id="visitorsChart"></canvas>
                        </div>
                    </div>

                    <!-- Revenue Card -->
                    <div class="dashboard-card revenue-card">
                        <div class="card-header">
                            <h3>Revenue</h3>
                            <i class="fas fa-external-link-alt"></i>
                        </div>
                        <div class="revenue-content">
                            <div class="revenue-chart">
                                <div class="revenue-circle">
                                    <span class="revenue-percentage">73%</span>
                                </div>
                            </div>
                            <div class="revenue-details">
                                <div class="revenue-legend">
                                    <div class="legend-item">
                                        <span class="dot service"></span>
                                        <span>Service</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="dot product"></span>
                                        <span>Product</span>
                                    </div>
                                </div>
                                <div class="revenue-amount">
                                    <span class="amount">€<?php echo number_format($total_revenue, 2); ?></span>
                                    <span class="growth">+13%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservations Section -->
            <div id="reservations" class="content-section">
                <div class="section-header">
                    <h1>Gestione Prenotazioni</h1>
                </div>
                <div class="reservations-table-container">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Ora</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefono</th>
                                <th>Servizio</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM prenotazioni ORDER BY data_prenotazione, orario");
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['data_prenotazione']}</td>
                                        <td>{$row['orario']}</td>
                                        <td>{$row['nome']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['telefono']}</td>
                                        <td>{$row['servizio']}</td>
                                        <td>
                                            <button class='btn-action edit'><i class='fas fa-edit'></i></button>
                                            <button class='btn-action delete'><i class='fas fa-trash'></i></button>
                                        </td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="action-buttons">
                    <form method="POST" action="svuota.php" style="display: inline;">
                        <button type="submit" class="btn-danger" onclick="return confirm('Sicuro di cancellare tutte le prenotazioni?')">
                            <i class="fas fa-trash-alt"></i> Svuota Prenotazioni
                        </button>
                    </form>
                </div>
            </div>

            <!-- Services Section -->
            <div id="services" class="content-section">
                <div class="section-header">
                    <h1>Gestione Servizi</h1>
                </div>
                <div class="add-service-form">
                    <form method="POST" action="aggiungi_servizio.php" class="modern-form">
                        <div class="form-group">
                            <input type="text" name="nome_servizio" placeholder="Nome Servizio" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="prezzo" placeholder="Prezzo (€)" required>
                        </div>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-plus"></i> Aggiungi Servizio
                        </button>
                    </form>
                </div>
                <div class="services-grid">
                    <?php
                    $services = $conn->query("SELECT * FROM servizi");
                    while ($service = $services->fetch_assoc()) {
                        echo "<div class='service-card'>
                                <div class='service-info'>
                                    <h3>{$service['nome']}</h3>
                                    <p class='service-price'>€{$service['prezzo']}</p>
                                </div>
                                <div class='service-actions'>
                                    <button class='btn-action edit'><i class='fas fa-edit'></i></button>
                                    <button class='btn-action delete'><i class='fas fa-trash'></i></button>
                                </div>
                              </div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Other sections (customers, analytics, settings) -->
            <div id="customers" class="content-section">
                <div class="section-header">
                    <h1>Clienti</h1>
                </div>
                <p>Sezione in sviluppo...</p>
            </div>

            <div id="analytics" class="content-section">
                <div class="section-header">
                    <h1>Analytics</h1>
                </div>
                <p>Sezione in sviluppo...</p>
            </div>

            <div id="settings" class="content-section">
                <div class="section-header">
                    <h1>Impostazioni</h1>
                </div>
                <p>Sezione in sviluppo...</p>
            </div>
        </div>
    </div>

    <script src="admin-script.js"></script>
</body>
</html>
```