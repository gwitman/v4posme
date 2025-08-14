@echo off

REM obtener el nombre de la variable
set /p nameBackup=Ingrese la fecha del backup YYYYMMDD: 
set dbvalue=posme
set filename=%dbvalue%_DB_%nameBackup%_%time:~0,2%_%time:~3,2%_%time:~6,2%.sql
set filename=%filename: =0%


"C:\xampp\mysql\bin\mysqldump.exe" -u root -proot1.2Blandon  -h localhost %dbvalue% > C:\TeamDS-Importacion\%filename%   -v --opt --events --routines --triggers --default-character-set=utf8 
echo %dbvalue%
echo %filename%
pause
