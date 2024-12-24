@echo off

echo "Password: root1.2Blandon"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost < C:\xampp\teamds2\nsSystem\v4posme\public\resource\file_sql\script_start_clear_base.sql

echo "fin"
pause
