<!-- Biometric Analytics Dashboard -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 space-y-3">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Biometric Analytics Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400">
            Monitor authentication patterns, security alerts, and device usage with zero debug noise and fully automated data refresh.
        </p>
       
    </div>

    <!-- Time Period Selector -->
    <div class="mb-6">
        <div class="flex space-x-2">
            <button onclick="setTimePeriod('24h')"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200 period-btn active"
                    data-period="24h">
                Last 24 Hours
            </button>
            <button onclick="setTimePeriod('7d')"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200 period-btn"
                    data-period="7d">
                Last 7 Days
            </button>
            <button onclick="setTimePeriod('30d')"
                    class="px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200 period-btn"
                    data-period="30d">
                Last 30 Days
            </button>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="overview-cards">
        <!-- Cards will be populated by JavaScript -->
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Success Rate Trend Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Success Rate Trend</h3>
                <button onclick="loadAnalyticsData()"
                        class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-sm text-gray-700 dark:text-gray-200 rounded-lg transition-colors duration-200">
                    <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-1"></i>
                    Refresh
                </button>
            </div>
            <div class="h-64">
                <canvas id="successRateChart"></canvas>
            </div>
        </div>

        <!-- Device Usage Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Device Usage</h3>
                <button onclick="loadAnalyticsData()"
                        class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-sm text-gray-700 dark:text-gray-200 rounded-lg transition-colors duration-200">
                    <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-1"></i>
                    Refresh
                </button>
            </div>
            <div class="h-64">
                <canvas id="deviceUsageChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Security Alerts Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Security Alerts</h3>
            <button onclick="refreshSecurityAlerts()"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                Refresh
            </button>
        </div>
        <div id="security-alerts-list" class="space-y-4 max-h-96 overflow-y-auto">
            <!-- Security alerts will be populated here -->
        </div>
    </div>

    <!-- Device Statistics Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Device Statistics</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Device Name</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Auth Attempts</th>
                        <th class="px-6 py-3">Success Rate</th>
                        <th class="px-6 py-3">Last Used</th>
                    </tr>
                </thead>
                <tbody id="device-stats-table">
                    <!-- Device stats will be populated here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let currentPeriod = '24h';
let analyticsData = {};
let securityAlerts = [];
let successRateChart = null;
let deviceUsageChart = null;
let autoRefreshTimer = null;

function ensureChartJsLoaded() {
    return new Promise((resolve, reject) => {
        if (window.Chart) {
            resolve();
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
        script.onload = () => resolve();
        script.onerror = (err) => reject(err);
        document.head.appendChild(script);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    ensureChartJsLoaded()
        .then(() => {
            initializeCharts();
            loadAnalyticsData();
            loadSecurityAlerts();
            startAutoRefresh();

            window.loadAnalyticsData = loadAnalyticsData;
            window.loadSecurityAlerts = loadSecurityAlerts;

            document.addEventListener('biometric:auth-success', () => {
                loadAnalyticsData();
                loadSecurityAlerts();
            });

            if (window.pendingAnalyticsRefresh) {
                loadAnalyticsData();
                loadSecurityAlerts();
                window.pendingAnalyticsRefresh = false;
            }
        })
        .catch((err) => {
            console.error('Failed to load Chart.js', err);
        });
});

function setTimePeriod(period) {
    currentPeriod = period;
    startAutoRefresh();

    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-blue-500', 'text-white');
        btn.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
    });

    document.querySelector(`[data-period="${period}"]`).classList.add('active', 'bg-blue-500', 'text-white');
    document.querySelector(`[data-period="${period}"]`).classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');

    loadAnalyticsData();
}

async function loadAnalyticsData() {
    try {
        const response = await fetch(`/api/biometric/fingerprint/analytics?period=${currentPeriod}`);
        const data = await response.json();

        if (data.success) {
            analyticsData = data.analytics;
            updateOverviewCards();
            updateCharts();
            updateDeviceStats();
        }
    } catch (error) {
        showNotification('Failed to load analytics data', 'error');
    }
}

async function loadSecurityAlerts() {
    try {
        const response = await fetch('/api/biometric/fingerprint/security-alerts?limit=20');
        const data = await response.json();

        if (data.success) {
            securityAlerts = data.alerts;
            updateSecurityAlerts();
        }
    } catch (error) {
        showNotification('Failed to load security alerts', 'error');
    }
}

function refreshSecurityAlerts() {
    loadSecurityAlerts();
}

function startAutoRefresh() {
    if (autoRefreshTimer) {
        clearInterval(autoRefreshTimer);
    }
    autoRefreshTimer = setInterval(() => {
        loadAnalyticsData();
        loadSecurityAlerts();
    }, 15000);
}

document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
        loadAnalyticsData();
        loadSecurityAlerts();
        startAutoRefresh();
    }
});

function updateOverviewCards() {
    const cards = [
        {
            title: 'Total Authentications',
            value: analyticsData.total_authentications || 0,
            icon: 'shield',
            color: 'blue'
        },
        {
            title: 'Success Rate',
            value: `${analyticsData.overall_success_rate || 0}%`,
            icon: 'check-circle',
            color: analyticsData.overall_success_rate >= 90 ? 'green' : analyticsData.overall_success_rate >= 70 ? 'yellow' : 'red'
        },
        {
            title: 'Security Alerts',
            value: analyticsData.security_summary?.total_alerts || 0,
            icon: 'alert-triangle',
            color: (analyticsData.security_summary?.total_alerts || 0) > 0 ? 'red' : 'green'
        },
        {
            title: 'Active Devices',
            value: analyticsData.device_statistics?.length || 0,
            icon: 'smartphone',
            color: 'purple'
        }
    ];

    const container = document.getElementById('overview-cards');
    container.innerHTML = cards.map(card => `
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">${card.title}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${card.value}</p>
                </div>
                <div class="p-3 bg-${card.color}-100 dark:bg-${card.color}-900/20 rounded-lg">
                    <i data-lucide="${card.icon}" class="w-6 h-6 text-${card.color}-600 dark:text-${card.color}-400"></i>
                </div>
            </div>
        </div>
    `).join('');

    if (window.lucide) {
        window.lucide.createIcons();
    }
}

function initializeCharts() {
    const ctx1 = document.getElementById('successRateChart').getContext('2d');
    const ctx2 = document.getElementById('deviceUsageChart').getContext('2d');

    successRateChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Success Rate (%)',
                data: [],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    deviceUsageChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 101, 101)',
                    'rgb(139, 92, 246)',
                    'rgb(251, 146, 60)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function updateCharts() {
    if (analyticsData.success_rate_trend) {
        successRateChart.data.labels = analyticsData.success_rate_trend.map(item => item.date);
        successRateChart.data.datasets[0].data = analyticsData.success_rate_trend.map(item => item.success_rate);
        successRateChart.update();
    }

    if (analyticsData.device_statistics) {
        const deviceData = analyticsData.device_statistics.filter(device => device.auth_attempts > 0);
        deviceUsageChart.data.labels = deviceData.map(device => device.name);
        deviceUsageChart.data.datasets[0].data = deviceData.map(device => device.auth_attempts);
        deviceUsageChart.update();
    }
}

function updateDeviceStats() {
    const tbody = document.getElementById('device-stats-table');

    if (!analyticsData.device_statistics || analyticsData.device_statistics.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No device data available</td></tr>';
        return;
    }

    tbody.innerHTML = analyticsData.device_statistics.map(device => {
        const successRate = device.auth_attempts > 0 ? Math.round((device.successful_auths / device.auth_attempts) * 100) : 0;
        const lastUsed = device.last_used ? new Date(device.last_used).toLocaleDateString() : 'Never';

        return `
            <tr class="border-t border-gray-200 dark:border-gray-700">
                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">${device.name}</td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 capitalize">${device.type}</td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">${device.auth_attempts}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${
                        successRate >= 90 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                        successRate >= 70 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                    }">
                        ${successRate}%
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">${lastUsed}</td>
            </tr>
        `;
    }).join('');
}

// Update security alerts
function updateSecurityAlerts() {
    const container = document.getElementById('security-alerts-list');

    if (!securityAlerts || securityAlerts.length === 0) {
        container.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-8">No security alerts found</p>';
        return;
    }

    container.innerHTML = securityAlerts.map(alert => {
        const severityColors = {
            'high': 'red',
            'medium': 'yellow',
            'low': 'gray'
        };

        const color = severityColors[alert.severity] || 'gray';
        const timestamp = new Date(alert.timestamp).toLocaleString();

        return `
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-${color}-100 text-${color}-800 dark:bg-${color}-900 dark:text-${color}-200 capitalize">
                                ${alert.severity} Risk
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">${timestamp}</span>
                        </div>
                        <p class="text-sm text-gray-900 dark:text-white mb-2">
                            <strong>${alert.device_name}</strong> - ${alert.action.replace('_', ' ')}
                        </p>
                        <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                            <p>IP: ${alert.ip_address}</p>
                            <p>User Agent: ${alert.user_agent?.substring(0, 50)}${alert.user_agent?.length > 50 ? '...' : ''}</p>
                        </div>
                    </div>
                    <div class="ml-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${
                            alert.success ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                        }">
                            ${alert.success ? 'Success' : 'Failed'}
                        </span>
                    </div>
                </div>
                ${alert.flags ? `
                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Security Flags:</p>
                        <div class="flex flex-wrap gap-1">
                            ${Object.keys(alert.flags).map(flag => `
                                <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                    ${flag.replace('_', ' ')}
                                </span>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
    }).join('');
}

// Show notification
function showNotification(message, type = 'info') {
    // Reuse existing notification system if available
    if (typeof window.showBiometricNotification === 'function') {
        window.showBiometricNotification(message, type);
    } else {
        console.log(`${type.toUpperCase()}: ${message}`);
    }
}
</script>
