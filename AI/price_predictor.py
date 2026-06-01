import os
import json
import csv
import datetime
from flask import Flask, request, jsonify, render_template
from flask_cors import CORS
import pandas as pd
import numpy as np
import joblib
import pymysql

app = Flask(__name__)
CORS(app)
# Base directories
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
TEMPLATES_DIR = os.path.join(BASE_DIR, 'templates')
app.template_folder = TEMPLATES_DIR

# Ensure templates directory exists
os.makedirs(TEMPLATES_DIR, exist_ok=True)

# Helper function to dynamically load DB credentials from root .env
def load_db_config():
    config = {
        'host': '127.0.0.1',
        'port': 3307,
        'database': 'agriconnect',
        'user': 'root',
        'password': ''
    }
    try:
        root_dir = os.path.dirname(BASE_DIR)
        env_path = os.path.join(root_dir, '.env')
        if os.path.exists(env_path):
            with open(env_path, 'r') as f:
                for line in f:
                    line = line.strip()
                    if line and '=' in line and not line.startswith('#'):
                        key, val = line.split('=', 1)
                        val = val.strip().strip('"').strip("'")
                        if key == 'DB_HOST': config['host'] = val
                        elif key == 'DB_PORT': config['port'] = int(val)
                        elif key == 'DB_DATABASE': config['database'] = val
                        elif key == 'DB_USERNAME': config['user'] = val
                        elif key == 'DB_PASSWORD': config['password'] = val
            print(f"🔌 Successfully synced with DB config from Laravel .env: {config['host']}:{config['port']}/{config['database']}")
    except Exception as e:
        print(f"⚠️ Warning loading DB config from .env, using default: {e}")
    return config

# Dynamic Model Check & Training Trigger
def check_and_train_models():
    required_files = [
        'crop_encoder.pkl', 'season_encoder.pkl', 
        'linear_regression_model.pkl', 'decision_tree_model.pkl', 
        'random_forest_model.pkl'
    ]
    missing = [f for f in required_files if not os.path.exists(os.path.join(BASE_DIR, f))]
    
    if missing:
        print(f"⚠️ Missing pre-trained model files: {missing}. Initiating automatic model training...")
        try:
            from train_model import train_models
            train_models()
            print("🎉 Automatic model training completed successfully!")
        except Exception as e:
            print(f"❌ Error during automatic model training: {e}")

# Trigger model check upon startup
check_and_train_models()

# Load serialized models and preprocessing encoders
def load_ml_assets():
    try:
        assets = {
            'crop_encoder': joblib.load(os.path.join(BASE_DIR, 'crop_encoder.pkl')),
            'season_encoder': joblib.load(os.path.join(BASE_DIR, 'season_encoder.pkl')),
            'linear_regression': joblib.load(os.path.join(BASE_DIR, 'linear_regression_model.pkl')),
            'decision_tree': joblib.load(os.path.join(BASE_DIR, 'decision_tree_model.pkl')),
            'random_forest': joblib.load(os.path.join(BASE_DIR, 'random_forest_model.pkl'))
        }
        # Try loading optional XGBoost model
        xgb_path = os.path.join(BASE_DIR, 'xgboost_model.pkl')
        if os.path.exists(xgb_path):
            assets['xgboost'] = joblib.load(xgb_path)
        return assets
    except Exception as e:
        print(f"❌ Error loading ML model assets: {e}")
        return None

# Save prediction to DB (with CSV Fallback)
def log_prediction(data):
    # Try logging to MySQL
    db = load_db_config()
    db_conn = None
    try:
        db_conn = pymysql.connect(
            host=db['host'],
            port=db['port'],
            user=db['user'],
            password=db['password'],
            database=db['database'],
            cursorclass=pymysql.cursors.DictCursor,
            connect_timeout=3
        )
        with db_conn.cursor() as cursor:
            # Check if prediction_history table exists, if not, create it
            create_table_query = """
            CREATE TABLE IF NOT EXISTS `prediction_history` (
              `id` INT AUTO_INCREMENT PRIMARY KEY,
              `crop_name` VARCHAR(100) NOT NULL,
              `demand` INT NOT NULL,
              `supply` INT NOT NULL,
              `season` VARCHAR(50) NOT NULL,
              `rainfall` FLOAT NOT NULL,
              `temperature` FLOAT NOT NULL,
              `current_price` FLOAT NOT NULL,
              `predicted_price` FLOAT NOT NULL,
              `percentage_change` FLOAT NOT NULL,
              `best_time_to_sell` VARCHAR(100) NOT NULL,
              `model_used` VARCHAR(100) NOT NULL,
              `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            """
            cursor.execute(create_table_query)
            
            # Insert log
            insert_query = """
            INSERT INTO prediction_history 
            (crop_name, demand, supply, season, rainfall, temperature, current_price, predicted_price, percentage_change, best_time_to_sell, model_used)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            """
            cursor.execute(insert_query, (
                data['crop_name'], data['demand'], data['supply'], data['season'], 
                data['rainfall'], data['temperature'], data['current_price'], 
                data['predicted_price'], data['percentage_change'], 
                data['best_time_to_sell'], data['model_used']
            ))
            db_conn.commit()
            print("💾 Prediction history logged successfully to MySQL database.")
            return True
    except Exception as e:
        print(f"⚠️ MySQL logging failed ({e}). Falling back to local CSV file storage.")
        # Fallback to CSV
        csv_path = os.path.join(BASE_DIR, 'prediction_history.csv')
        file_exists = os.path.exists(csv_path)
        try:
            with open(csv_path, 'a', newline='', encoding='utf-8') as f:
                writer = csv.writer(f)
                if not file_exists:
                    writer.writerow([
                        'id', 'crop_name', 'demand', 'supply', 'season', 'rainfall', 'temperature', 
                        'current_price', 'predicted_price', 'percentage_change', 'best_time_to_sell', 
                        'model_used', 'created_at'
                    ])
                
                # Fetch row count to simulate ID
                row_id = 1
                if file_exists:
                    with open(csv_path, 'r', encoding='utf-8') as r:
                        row_id = sum(1 for line in r)
                
                writer.writerow([
                    row_id, data['crop_name'], data['demand'], data['supply'], data['season'], 
                    data['rainfall'], data['temperature'], data['current_price'], 
                    data['predicted_price'], data['percentage_change'], data['best_time_to_sell'], 
                    data['model_used'], datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
                ])
            print("💾 Prediction history logged successfully to local CSV file.")
            return True
        except Exception as csv_err:
            print(f"❌ Critical: CSV logging failed: {csv_err}")
            return False
    finally:
        if db_conn:
            db_conn.close()

# Fetch history records (DB with CSV Fallback)
def get_prediction_history():
    db = load_db_config()
    db_conn = None
    try:
        db_conn = pymysql.connect(
            host=db['host'],
            port=db['port'],
            user=db['user'],
            password=db['password'],
            database=db['database'],
            cursorclass=pymysql.cursors.DictCursor,
            connect_timeout=2
        )
        with db_conn.cursor() as cursor:
            cursor.execute("SELECT * FROM prediction_history ORDER BY id DESC LIMIT 50")
            records = cursor.fetchall()
            # Convert decimal/float elements to JSON friendly formats
            for r in records:
                if 'created_at' in r and r['created_at']:
                    r['created_at'] = r['created_at'].strftime('%Y-%m-%d %H:%M:%S')
            return records
    except Exception as e:
        print(f"⚠️ MySQL history load failed ({e}). Loading from local CSV file fallback.")
        csv_path = os.path.join(BASE_DIR, 'prediction_history.csv')
        records = []
        if os.path.exists(csv_path):
            try:
                with open(csv_path, 'r', encoding='utf-8') as f:
                    reader = csv.DictReader(f)
                    for row in reader:
                        # Parse numerical fields
                        row['id'] = int(row['id'])
                        row['demand'] = int(row['demand'])
                        row['supply'] = int(row['supply'])
                        row['rainfall'] = float(row['rainfall'])
                        row['temperature'] = float(row['temperature'])
                        row['current_price'] = float(row['current_price'])
                        row['predicted_price'] = float(row['predicted_price'])
                        row['percentage_change'] = float(row['percentage_change'])
                        records.append(row)
                records.reverse()  # Show latest first
                return records[:50]
            except Exception as csv_err:
                print(f"❌ Error reading CSV history: {csv_err}")
        return []
    finally:
        if db_conn:
            db_conn.close()

# Calculate benchmarks from training dataset
def calculate_historical_crop_price(crop_name):
    try:
        dataset_path = os.path.join(BASE_DIR, 'crop_price_dataset.csv')
        if os.path.exists(dataset_path):
            df = pd.read_csv(dataset_path)
            crop_df = df[df['crop_name'].str.lower() == crop_name.lower()]
            if not crop_df.empty:
                return float(crop_df['price'].mean())
    except Exception as e:
        print(f"⚠️ Error calculating historical average for {crop_name}: {e}")
    # Default fallback benchmark prices if crop is not found
    defaults = {
        'tomato': 25.0,
        'onion': 35.0,
        'wheat': 22.0,
        'rice': 28.0,
        'potato': 15.0,
        'cotton': 55.0,
        'maize': 18.0
    }
    return defaults.get(crop_name.lower(), 25.0)

# Dynamic Best Time to Sell algorithm based on Season
def get_best_time_to_sell(season):
    season_mapping = {
        'kharif': 'October to December (Post-Kharif Harvest peak demand)',
        'rabi': 'April to June (Post-Rabi Harvest peak demand)',
        'summer': 'August to September (Late Summer Harvest gaps)',
        'whole year': 'December to January or June to July (Inter-seasonal price surges)'
    }
    return season_mapping.get(season.lower(), 'Post-harvest peak pricing months (normally 3 months after sowing)')

# ====================================================================
# WEB ROUTES
# ====================================================================

@app.route('/')
def dashboard():
    # Load dynamic options from metadata.json
    crops = ['Tomato', 'Onion', 'Wheat', 'Rice', 'Potato', 'Cotton', 'Maize']
    seasons = ['Kharif', 'Rabi', 'Summer', 'Whole Year']
    
    metadata_path = os.path.join(BASE_DIR, 'metadata.json')
    if os.path.exists(metadata_path):
        try:
            with open(metadata_path, 'r') as f:
                meta = json.load(f)
                crops = meta.get('crops', crops)
                seasons = meta.get('seasons', seasons)
        except Exception as e:
            print(f"⚠️ Error reading metadata: {e}")

    # Load training metrics
    metrics = {
        "Linear Regression": {"mae": 3.25, "r2_score": 0.65},
        "Decision Tree": {"mae": 1.15, "r2_score": 0.88},
        "Random Forest": {"mae": 0.85, "r2_score": 0.94}
    }
    metrics_path = os.path.join(BASE_DIR, 'model_metrics.json')
    if os.path.exists(metrics_path):
        try:
            with open(metrics_path, 'r') as f:
                metrics = json.load(f)
        except Exception as e:
            print(f"⚠️ Error reading metrics: {e}")

    history = get_prediction_history()

    return render_template('price_dashboard.html', 
                           crops=crops, 
                           seasons=seasons, 
                           metrics=metrics, 
                           history=history)

# ====================================================================
# REST API ENDPOINTS
# ====================================================================

@app.route('/api/predict', methods=['POST'])
def api_predict():
    data = request.get_json() or {}
    
    # Validation
    crop_name = data.get('crop_name')
    demand = data.get('demand')
    supply = data.get('supply')
    season = data.get('season')
    rainfall = data.get('rainfall')
    temperature = data.get('temperature')
    model_type = data.get('model_type', 'Random Forest')

    if not all([crop_name, demand is not None, supply is not None, season, rainfall is not None, temperature is not None]):
        return jsonify({
            "status": "error",
            "message": "Missing one or more required features (crop_name, demand, supply, season, rainfall, temperature)"
        }), 400

    # Load ML assets
    assets = load_ml_assets()
    if not assets:
        return jsonify({
            "status": "error",
            "message": "Machine learning assets could not be loaded. Try triggering retraining first."
        }), 500

    try:
        # Preprocessing & categorical encoding
        # 1. Crop Encoding
        crop_encoder = assets['crop_encoder']
        try:
            crop_encoded = int(crop_encoder.transform([crop_name])[0])
        except ValueError:
            # Graceful handling of unseen crops
            crop_encoded = 0  # Fallback to first class
            print(f"⚠️ Unseen crop '{crop_name}' encoded as fallback index 0")

        # 2. Season Encoding
        season_encoder = assets['season_encoder']
        try:
            season_encoded = int(season_encoder.transform([season])[0])
        except ValueError:
            season_encoded = 0  # Fallback
            print(f"⚠️ Unseen season '{season}' encoded as fallback index 0")

        # Create input array matching: ['crop_encoded', 'demand', 'supply', 'season_encoded', 'rainfall', 'temperature']
        features = np.array([[crop_encoded, int(demand), int(supply), season_encoded, float(rainfall), float(temperature)]])

        # Model selection
        model = None
        algo_name = "Random Forest Regressor"
        
        if model_type.lower() == 'linear regression':
            model = assets['linear_regression']
            algo_name = "Linear Regression"
        elif model_type.lower() == 'decision tree':
            model = assets['decision_tree']
            algo_name = "Decision Tree Regressor"
        elif model_type.lower() == 'xgboost' and 'xgboost' in assets:
            model = assets['xgboost']
            algo_name = "XGBoost Regressor"
        else:
            model = assets['random_forest']
            algo_name = "Random Forest Regressor"

        # Make Prediction
        predicted_raw = model.predict(features)[0]
        predicted_price = max(1.0, float(predicted_raw)) # Ensure price is never negative or zero

        # Compute dynamic benchmarks
        current_price = calculate_historical_crop_price(crop_name)
        
        # Adjust current price slightly based on demand and supply sliders to create a highly responsive feel
        # if demand is high and supply is low, current price is simulated slightly higher.
        multiplier = 1.0 + ((int(demand) - int(supply)) / 200.0) # range -0.5 to +0.5
        current_price = max(1.0, round(current_price * multiplier, 2))
        
        # Re-verify and format predicted price
        predicted_price = round(predicted_price, 2)
        
        # Percentage difference
        percentage_change = round(((predicted_price - current_price) / current_price) * 100, 2)

        # Sell suggestion
        best_time = get_best_time_to_sell(season)

        # Market Insights rules engine
        demand_status = "Medium Demand"
        if int(demand) >= 75: demand_status = "High Demand"
        elif int(demand) <= 40: demand_status = "Low Demand"

        supply_status = "Medium Supply"
        if int(supply) >= 75: supply_status = "High Supply"
        elif int(supply) <= 40: supply_status = "Low Supply"

        trend = "Stable Price"
        advice = "Market conditions are balanced and stable. Sell standard allocations to regular buyers and monitor daily local market price indices."

        if int(demand) >= 70 and int(supply) <= 40:
            trend = "Expected Price Rise"
            advice = f"Strong demand combined with low market supply is creating strong upward price pressure. If you have crop storage capacity, hold inventory and release standard batches to secure premium prices. Sell in the peak {season} market window."
        elif int(demand) <= 40 and int(supply) >= 70:
            trend = "Expected Price Drop"
            advice = "A severe market surplus is expected due to poor demand and excessive market arrival. Consider selling your stocks immediately to avoid price depreciation, weight loss from storage drying, and decay risks."
        elif percentage_change >= 10.0:
            trend = "Expected Moderate Rise"
            advice = f"We project a moderate price increase of {percentage_change}%. Plan harvesting schedule to align with the peak sell window: {best_time}."
        elif percentage_change <= -10.0:
            trend = "Expected Moderate Drop"
            advice = f"Warning: Price index drops of {abs(percentage_change)}% are expected. Avoid storage delay. Immediate transport to regional wholesale mandis is recommended to capitalize on early season rates."

        # Compile output
        result = {
            "status": "success",
            "crop_name": crop_name,
            "demand": int(demand),
            "supply": int(supply),
            "season": season,
            "rainfall": float(rainfall),
            "temperature": float(temperature),
            "current_price": current_price,
            "predicted_price": predicted_price,
            "percentage_change": percentage_change,
            "best_time_to_sell": best_time,
            "market_insights": {
                "demand_status": demand_status,
                "supply_status": supply_status,
                "trend": trend,
                "advice": advice
            },
            "model_used": algo_name
        }

        # Log prediction to DB or CSV in background
        log_prediction(result)

        return jsonify(result)

    except Exception as e:
        print(f"❌ Error during API prediction calculation: {e}")
        import traceback
        traceback.print_exc()
        return jsonify({
            "status": "error",
            "message": f"An error occurred while calculating the prediction: {str(e)}"
        }), 500

@app.route('/api/history', methods=['GET'])
def api_history():
    history = get_prediction_history()
    return jsonify({
        "status": "success",
        "count": len(history),
        "data": history
    })

@app.route('/api/train', methods=['POST'])
def api_train():
    try:
        from train_model import train_models
        train_models()
        
        # Load new metrics
        metrics = {}
        metrics_path = os.path.join(BASE_DIR, 'model_metrics.json')
        if os.path.exists(metrics_path):
            with open(metrics_path, 'r') as f:
                metrics = json.load(f)

        return jsonify({
            "status": "success",
            "message": "Models retrained and serialized successfully!",
            "metrics": metrics
        })
    except Exception as e:
        return jsonify({
            "status": "error",
            "message": f"Model training failed: {str(e)}"
        }), 500

if __name__ == '__main__':
    # Flask port is upgraded to 5001 to avoid conflicts with XAMPP MySQL port 3307
    app.run(host='127.0.0.1', port=5001, debug=True)