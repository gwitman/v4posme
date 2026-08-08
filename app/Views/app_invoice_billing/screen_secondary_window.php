<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor de Facturación</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }
        .container {
            width: 90%;
            max-width: 900px;
            text-align: center;
        }
        .header {
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 2rem;
            font-weight: 300;
            color: #a8d8ea;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .totals-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        .total-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px 30px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .total-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .total-card .label {
            font-size: 1.1rem;
            font-weight: 400;
            color: #a8d8ea;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }
        .total-card .value {
            font-size: 3rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(168, 216, 234, 0.3);
        }
        .total-card.highlight {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.15) 0%, rgba(56, 142, 60, 0.15) 100%);
            border-color: rgba(76, 175, 80, 0.4);
        }
        .total-card.highlight .label {
            color: #81c784;
            font-size: 1.3rem;
        }
        .total-card.highlight .value {
            font-size: 4.5rem;
            color: #a5d6a7;
            text-shadow: 0 4px 20px rgba(76, 175, 80, 0.4);
        }
        .status-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            font-size: 0.85rem;
            color: #607d8b;
        }
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #4caf50;
            animation: pulse 2s infinite;
        }
        .status-dot.disconnected {
            background: #f44336;
            animation: none;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }
        .no-data {
            font-size: 1.5rem;
            color: #607d8b;
            display: none;
        }
        .no-data.visible {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Monitor de Facturación</h1>
        </div>

        <div id="contentArea">
            <div class="totals-grid">
                <div class="total-card">
                    <div class="label">Sub Total</div>
                    <div class="value" id="displaySubTotal">0.00</div>
                </div>
                <div class="total-card">
                    <div class="label">Descuento</div>
                    <div class="value" id="displayDiscount">0.00</div>
                </div>
                <div class="total-card highlight">
                    <div class="label">Total a Pagar</div>
                    <div class="value" id="displayTotal">0.00</div>
                </div>
            </div>
        </div>

        <p class="no-data" id="noDataMessage">Esperando datos de facturación...</p>

        <div class="status-bar">
            <span class="status-dot" id="statusDot"></span>
            <span id="statusText">Conectado - Escuchando cambios</span>
        </div>
    </div>

    <script>
        (function() {
            const STORAGE_KEY = 'posme_billing_totals';
            let lastUpdate = 0;

            function formatNumber(value) {
                let num = parseFloat(value);
                if (isNaN(num)) return '0.00';
                return num.toLocaleString('es-NI', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function updateDisplay() {
                try {
                    let raw = localStorage.getItem(STORAGE_KEY);
                    if (!raw) {
                        document.getElementById('noDataMessage').classList.add('visible');
                        document.getElementById('statusDot').classList.add('disconnected');
                        document.getElementById('statusText').textContent = 'Sin datos - Abra la pantalla de facturación';
                        return;
                    }

                    let data = JSON.parse(raw);
                    document.getElementById('noDataMessage').classList.remove('visible');
                    document.getElementById('statusDot').classList.remove('disconnected');
                    document.getElementById('statusText').textContent = 'Conectado - Última actualización: ' + (data.timestamp ? new Date(data.timestamp).toLocaleTimeString() : 'N/A');

                    document.getElementById('displaySubTotal').textContent = formatNumber(data.subTotal || 0);
                    document.getElementById('displayDiscount').textContent = formatNumber(data.discount || 0);
                    document.getElementById('displayTotal').textContent = formatNumber(data.total || 0);

                } catch (e) {
                    console.error('Error al leer datos:', e);
                }
            }

            // Escuchar cambios en localStorage desde otra pestaña/ventana
            window.addEventListener('storage', function(e) {
                if (e.key === STORAGE_KEY) {
                    updateDisplay();
                }
            });

            // Polling como fallback (para mismo origen, misma pestaña no dispara storage event)
            setInterval(updateDisplay, 1000);

            // Lectura inicial
            updateDisplay();
        })();
    </script>
</body>
</html>
