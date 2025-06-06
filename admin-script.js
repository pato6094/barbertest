// Navigation functionality
function showSection(sectionId) {
    // Hide all sections
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Remove active class from all nav items
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.classList.remove('active');
    });
    
    // Show selected section
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
    }
    
    // Add active class to clicked nav item
    const targetNav = document.querySelector(`[onclick="showSection('${sectionId}')"]`);
    if (targetNav) {
        targetNav.classList.add('active');
    }
}

// Service tabs functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all tabs
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
        });
    });
    
    // Search functionality
    const searchInput = document.querySelector('.search-bar input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            // Add search logic here
            console.log('Searching for:', searchTerm);
        });
    }
    
    // Notification click handler
    const notifications = document.querySelector('.notifications');
    if (notifications) {
        notifications.addEventListener('click', function() {
            alert('Hai ' + document.querySelector('.notification-badge').textContent + ' nuove prenotazioni oggi!');
        });
    }
    
    // User profile dropdown (placeholder)
    const userProfile = document.querySelector('.user-profile');
    if (userProfile) {
        userProfile.addEventListener('click', function() {
            // Add dropdown menu logic here
            console.log('User profile clicked');
        });
    }
    
    // Action buttons functionality
    const editButtons = document.querySelectorAll('.btn-action.edit');
    const deleteButtons = document.querySelectorAll('.btn-action.delete');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            alert('Funzione di modifica in sviluppo');
        });
    });
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Sei sicuro di voler eliminare questo elemento?')) {
                // Add delete logic here
                console.log('Delete confirmed');
            }
        });
    });
    
    // Chart animation (placeholder)
    animateCharts();
});

// Chart animation function
function animateCharts() {
    const chartCircles = document.querySelectorAll('.chart-circle, .chart-circle-small, .revenue-circle');
    
    chartCircles.forEach(circle => {
        // Add subtle rotation animation
        circle.style.animation = 'rotate 10s linear infinite';
    });
}

// Add CSS animation for charts
const style = document.createElement('style');
style.textContent = `
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .chart-circle, .chart-circle-small, .revenue-circle {
        animation: rotate 20s linear infinite;
    }
    
    .chart-circle:hover, .chart-circle-small:hover, .revenue-circle:hover {
        animation-play-state: paused;
    }
`;
document.head.appendChild(style);

// Real-time updates (placeholder)
function updateStats() {
    // This would typically fetch new data from the server
    console.log('Updating statistics...');
}

// Update stats every 30 seconds
setInterval(updateStats, 30000);

// Smooth scrolling for internal links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add loading states for buttons
function addLoadingState(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Caricamento...';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 2000);
}

// Apply loading state to form submissions
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton) {
            addLoadingState(submitButton);
        }
    });
});
```