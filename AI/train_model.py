import os
import json
import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder
from sklearn.linear_model import LinearRegression
from sklearn.tree import DecisionTreeRegressor
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_absolute_error, r2_score
import joblib

def train_models():
    print("🚀 Starting Machine Learning Model Training Workflow...")

    # Define paths
    base_dir = os.path.dirname(os.path.abspath(__file__))
    dataset_path = os.path.join(base_dir, 'crop_price_dataset.csv')
    
    if not os.path.exists(dataset_path):
        raise FileNotFoundError(f"❌ Dataset not found at: {dataset_path}")

    # Load dataset
    print(f"📊 Loading dataset from: {dataset_path}")
    df = pd.read_csv(dataset_path)
    print(f"✅ Loaded {len(df)} records.")

    # Check columns
    required_cols = ['crop_name', 'demand', 'supply', 'season', 'rainfall', 'temperature', 'price']
    for col in required_cols:
        if col not in df.columns:
            raise ValueError(f"❌ Missing required column in dataset: {col}")

    # Encoding categorical columns
    print("🏷️ Preprocessing data & encoding categorical features...")
    crop_encoder = LabelEncoder()
    df['crop_encoded'] = crop_encoder.fit_transform(df['crop_name'])
    
    season_encoder = LabelEncoder()
    df['season_encoded'] = season_encoder.fit_transform(df['season'])

    # Save Encoders
    joblib.dump(crop_encoder, os.path.join(base_dir, 'crop_encoder.pkl'))
    joblib.dump(season_encoder, os.path.join(base_dir, 'season_encoder.pkl'))
    print("💾 Saved categorical encoders successfully.")

    # Save list of unique crops and seasons for frontend dropdowns
    metadata = {
        "crops": sorted(list(crop_encoder.classes_)),
        "seasons": sorted(list(season_encoder.classes_))
    }
    with open(os.path.join(base_dir, 'metadata.json'), 'w') as f:
        json.dump(metadata, f, indent=4)

    # Define Features and Target
    # Features: [crop_encoded, demand, supply, season_encoded, rainfall, temperature]
    feature_cols = ['crop_encoded', 'demand', 'supply', 'season_encoded', 'rainfall', 'temperature']
    X = df[feature_cols]
    y = df['price']

    # Split dataset into training and testing sets (80% train, 20% test)
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    print(f"📈 Split data into training ({len(X_train)} samples) and testing ({len(X_test)} samples).")

    # Initialize models
    models = {
        "Linear Regression": LinearRegression(),
        "Decision Tree": DecisionTreeRegressor(random_state=42, max_depth=8),
        "Random Forest": RandomForestRegressor(random_state=42, n_estimators=100, max_depth=8)
    }

    metrics = {}

    for name, model in models.items():
        print(f"\n⚡ Training {name} Model...")
        # Train model
        model.fit(X_train, y_train)
        
        # Make predictions on test set
        y_pred = model.predict(X_test)
        
        # Calculate metrics
        mae = mean_absolute_error(y_test, y_pred)
        r2 = r2_score(y_test, y_pred)
        
        print(f"   🎯 Mean Absolute Error (MAE): ₹{mae:.2f}")
        print(f"   🎯 R-squared (R²) Score: {r2:.4f}")

        # Store metrics
        metrics[name] = {
            "mae": round(float(mae), 2),
            "r2_score": round(float(r2), 4)
        }

        # Serialize model
        model_filename = name.lower().replace(" ", "_") + "_model.pkl"
        model_path = os.path.join(base_dir, model_filename)
        joblib.dump(model, model_path)
        print(f"💾 Saved {name} model as: {model_filename}")

    # Try optional XGBoost
    try:
        from xgboost import XGBRegressor
        print("\n⚡ Optional: Training XGBoost Regressor...")
        xgb_model = XGBRegressor(random_state=42, n_estimators=100, max_depth=6, learning_rate=0.1)
        xgb_model.fit(X_train, y_train)
        y_pred_xgb = xgb_model.predict(X_test)
        
        mae_xgb = mean_absolute_error(y_test, y_pred_xgb)
        r2_xgb = r2_score(y_test, y_pred_xgb)
        
        print(f"   🎯 XGBoost MAE: ₹{mae_xgb:.2f}")
        print(f"   🎯 XGBoost R² Score: {r2_xgb:.4f}")
        
        metrics["XGBoost"] = {
            "mae": round(float(mae_xgb), 2),
            "r2_score": round(float(r2_xgb), 4)
        }
        
        joblib.dump(xgb_model, os.path.join(base_dir, "xgboost_model.pkl"))
        print("💾 Saved XGBoost model as: xgboost_model.pkl")
    except ImportError:
        print("\nℹ️ XGBoost package not installed. Skipping optional XGBoost training.")

    # Save metrics JSON
    metrics_path = os.path.join(base_dir, 'model_metrics.json')
    with open(metrics_path, 'w') as f:
        json.dump(metrics, f, indent=4)
    print(f"\n📊 Saved model metrics summary to: {metrics_path}")
    print("🎉 All models trained and serialized successfully!")

if __name__ == "__main__":
    train_models()
