@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 25px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
    }
    .page-title {
        font-weight: 800;
        color: #1e7e34;
        margin-bottom: 30px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }
    .form-label {
        font-weight: 600;
        color: #333;
    }
    .custom-range::-webkit-slider-thumb {
        background: #28a745;
    }
    .result-box {
        text-align: center;
        padding: 20px;
        border-radius: 15px;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border: 2px dashed #dee2e6;
    }
    .price-value {
        font-size: 3.5rem;
        font-weight: 900;
        color: #28a745;
        margin: 10px 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }
    .timestamp {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .insight-box {
        margin-top: 20px;
        padding: 20px;
        border-radius: 15px;
        background: #e9f5ec;
        border-left: 5px solid #28a745;
    }
    .insight-title {
        font-weight: 700;
        color: #1e7e34;
    }
    .loader {
        display: none;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #28a745;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>

<div class="container">
    <h2 class="page-title text-center"><i class="bi bi-robot"></i> AI Crop Price Predictor</h2>
    
    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-4">
            <div class="glass-card">
                <h5 class="mb-4 fw-bold text-secondary"><i class="bi bi-sliders"></i> Input Parameters</h5>
                <form id="prediction-form">
                    <div class="mb-3">
                        <label class="form-label">Crop Name</label>
                        <select class="form-select shadow-sm" id="crop_name" required>
                            <option value="Tomato">Tomato</option>
                            <option value="Onion">Onion</option>
                            <option value="Wheat">Wheat</option>
                            <option value="Rice">Rice</option>
                            <option value="Potato">Potato</option>
                            <option value="Cotton">Cotton</option>
                            <option value="Maize">Maize</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Season</label>
                        <select class="form-select shadow-sm" id="season" required>
                            <option value="Kharif">Kharif</option>
                            <option value="Rabi">Rabi</option>
                            <option value="Summer">Summer</option>
                            <option value="Whole Year">Whole Year</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Market Demand (<span id="demand-val">50</span>)</label>
                        <input type="range" class="form-range custom-range" id="demand" min="0" max="100" value="50" oninput="document.getElementById('demand-val').innerText = this.value">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Market Supply (<span id="supply-val">50</span>)</label>
                        <input type="range" class="form-range custom-range" id="supply" min="0" max="100" value="50" oninput="document.getElementById('supply-val').innerText = this.value">
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Rainfall (mm)</label>
                            <input type="number" class="form-control shadow-sm" id="rainfall" value="120" required step="any">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Temp (°C)</label>
                            <input type="number" class="form-control shadow-sm" id="temperature" value="25" required step="any">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 shadow fw-bold py-2" id="predict-btn">
                        <i class="bi bi-lightning-charge-fill"></i> Predict Price
                    </button>
                </form>
            </div>
        </div>

        <!-- Result Section -->
        <div class="col-lg-8">
            <div class="glass-card">
                <h5 class="mb-3 fw-bold text-secondary"><i class="bi bi-display"></i> Prediction Output</h5>
                <div class="loader" id="loader"></div>
                
                <div id="result-container" style="display: none;">
                    <div class="result-box">
                        <p class="mb-0 text-muted fw-bold">Predicted Price per Quintal</p>
                        <div class="price-value">₹<span id="predicted-price">0.00</span></div>
                        <p class="mb-0 fw-bold">
                            Trend: <span id="trend-badge" class="badge bg-primary fs-6">Stable</span> 
                            (<span id="percentage-change">0%</span>)
                        </p>
                        <p class="timestamp mt-2"><i class="bi bi-clock"></i> <span id="timestamp"></span></p>
                    </div>
                    
                    <div class="insight-box shadow-sm">
                        <h6 class="insight-title"><i class="bi bi-lightbulb-fill text-warning"></i> AI Recommendation</h6>
                        <p id="ai-advice" class="mb-1 mt-2 fw-medium text-dark"></p>
                        <hr>
                        <p class="mb-0 small text-muted"><strong>Best Time to Sell:</strong> <span id="best-time"></span></p>
                        <p class="mb-0 small text-muted"><strong>Model Used:</strong> <span id="model-used"></span></p>
                    </div>
                </div>
                
                <div id="initial-state" class="text-center py-5 text-muted">
                    <i class="bi bi-graph-up-arrow" style="font-size: 3rem; color: #dee2e6;"></i>
                    <p class="mt-3">Enter parameters and click Predict to see the AI output.</p>
                </div>
            </div>
            
            <div class="glass-card">
                <h5 class="mb-3 fw-bold text-secondary"><i class="bi bi-bar-chart-line-fill"></i> Recent Predictions Trend</h5>
                <div class="chart-container">
                    <canvas id="historyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- History Table -->
    <div class="row">
        <div class="col-12">
            <div class="glass-card">
                <h5 class="mb-4 fw-bold text-secondary"><i class="bi bi-clock-history"></i> Prediction History</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Crop</th>
                                <th>Season</th>
                                <th>Demand</th>
                                <th>Supply</th>
                                <th>Predicted Price</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody id="history-tbody">
                            <tr><td colspan="7" class="text-center text-muted">Loading history...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Chart
        const ctx = document.getElementById('historyChart').getContext('2d');
        let historyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Predicted Price (₹)',
                    data: [],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: false }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Load History
        function loadHistory() {
            fetch('http://127.0.0.1:5001/api/history')
                .then(async res => {
    if (!res.ok) {
        const text = await res.text();
        throw new Error("HTTP " + res.status + ": " + text);
    }
    return res.json();
})
                .then(data => {
                    if(data.status === 'success' && data.data.length > 0) {
                        const tbody = document.getElementById('history-tbody');
                        tbody.innerHTML = '';
                        
                        // Update Table
                        data.data.slice(0, 10).forEach(item => {
                            let trendIcon = item.percentage_change > 0 ? '<i class="bi bi-arrow-up-right text-danger"></i>' : (item.percentage_change < 0 ? '<i class="bi bi-arrow-down-right text-success"></i>' : '<i class="bi bi-arrow-right text-warning"></i>');
                            
                            tbody.innerHTML += `
                                <tr>
                                    <td class="small text-muted">${item.created_at}</td>
                                    <td class="fw-bold">${item.crop_name}</td>
                                    <td><span class="badge bg-secondary">${item.season}</span></td>
                                    <td>${item.demand}</td>
                                    <td>${item.supply}</td>
                                    <td class="fw-bold text-success">₹${item.predicted_price}</td>
                                    <td>${trendIcon} ${item.percentage_change}%</td>
                                </tr>
                            `;
                        });
                        
                        // Update Chart
                        const chartData = [...data.data].reverse().slice(0, 15);
                        historyChart.data.labels = chartData.map(d => d.crop_name + ' (' + d.created_at.split(' ')[0] + ')');
                        historyChart.data.datasets[0].data = chartData.map(d => d.predicted_price);
                        historyChart.update();
                    } else {
                        document.getElementById('history-tbody').innerHTML = '<tr><td colspan="7" class="text-center text-muted">No history found.</td></tr>';
                    }
                })
                .catch(err => {
                    console.error('Error fetching history:', err);
                    document.getElementById('history-tbody').innerHTML = '<tr><td colspan="7" class="text-center text-danger">Failed to load history. Is the Python API running?</td></tr>';
                });
        }
        
        // Load initial history
        loadHistory();

        // Handle Form Submission
        document.getElementById('prediction-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // UI States
            const btn = document.getElementById('predict-btn');
            const loader = document.getElementById('loader');
            const resultContainer = document.getElementById('result-container');
            const initialState = document.getElementById('initial-state');
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Predicting...';
            initialState.style.display = 'none';
            resultContainer.style.display = 'none';
            loader.style.display = 'block';

            const payload = {
                crop_name: document.getElementById('crop_name').value,
                season: document.getElementById('season').value,
                demand: parseInt(document.getElementById('demand').value),
                supply: parseInt(document.getElementById('supply').value),
                rainfall: parseFloat(document.getElementById('rainfall').value),
                temperature: parseFloat(document.getElementById('temperature').value),
                model_type: 'Linear Regression' // Forced per requirements
            };

            fetch('http://127.0.0.1:5001/api/predict', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                loader.style.display = 'none';
                if(data.status === 'success') {
                    // Update UI
                    document.getElementById('predicted-price').innerText = data.predicted_price.toFixed(2);
                    
                    let pc = data.percentage_change;
                    let trendBadge = document.getElementById('trend-badge');
                    if (pc > 0) {
                        trendBadge.className = 'badge bg-danger fs-6';
                        trendBadge.innerText = data.market_insights.trend;
                        document.getElementById('percentage-change').innerText = '+' + pc + '%';
                        document.getElementById('percentage-change').className = 'text-danger';
                    } else if (pc < 0) {
                        trendBadge.className = 'badge bg-success fs-6';
                        trendBadge.innerText = data.market_insights.trend;
                        document.getElementById('percentage-change').innerText = pc + '%';
                        document.getElementById('percentage-change').className = 'text-success';
                    } else {
                        trendBadge.className = 'badge bg-warning text-dark fs-6';
                        trendBadge.innerText = 'Stable';
                        document.getElementById('percentage-change').innerText = '0%';
                        document.getElementById('percentage-change').className = 'text-warning';
                    }
                    
                    document.getElementById('timestamp').innerText = new Date().toLocaleString();
                    document.getElementById('ai-advice').innerText = data.market_insights.advice;
                    document.getElementById('best-time').innerText = data.best_time_to_sell;
                    document.getElementById('model-used').innerText = data.model_used;
                    
                    resultContainer.style.display = 'block';
                    
                    // Reload history to show new entry
                    setTimeout(loadHistory, 1000);
                } else {
                    alert('Error: ' + data.message);
                    initialState.style.display = 'block';
                }
            })
            .catch(async err => {
    console.error("Prediction Error:", err);

    loader.style.display = 'none';
    initialState.style.display = 'block';

    alert("Actual Error: " + err.message);
})
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-lightning-charge-fill"></i> Predict Price';
            });
        });
    });
</script>
@endsection
