@echo off
title AgriConnect AI - Flask Predictor Server
color 0B
echo ====================================================================
echo             AgriConnect AI: Flask Price Predictor Server
echo ====================================================================
echo.
echo Active Python Path: C:\Users\sarat\AppData\Local\Programs\Python\Python310\python.exe
echo Flask Port: 5001 (Avoiding port 3307 conflict with MySQL)
echo.
echo Starting Flask App...
"C:\Users\sarat\AppData\Local\Programs\Python\Python310\python.exe" AI/price_predictor.py
echo.
echo ====================================================================
pause
