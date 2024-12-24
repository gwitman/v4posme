@echo off
set script_sql=%1
echo "Password: root1.2Blandon"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost < C:\xampp\teamds2\nsSystem\v4posme\public\resource\file_sql\%script_sql%

echo "fin"
pause
