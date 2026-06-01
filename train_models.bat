@echo off
title AgriConnect AI - Model Trainer
color 0A
echo ====================================================================
echo             AgriConnect AI: Model Training Workflow
echo ====================================================================
echo.
echo Active Python Path: C:\Users\sarat\AppData\Local\Programs\Python\Python310\python.exe
echo Preparing to train Linear Regression, Decision Tree, and Random Forest models...
echo.
"C:\Users\sarat\AppData\Local\Programs\Python\Python310\python.exe" AI/train_model.py
echo.
echo ====================================================================
pause
