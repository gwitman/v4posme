@echo off
set dbvalue=biometric
set filename=%dbvalue%_DB_%date:~11,4%%date:~8,2%%date:~5,2%_%time:~0,2%_%time:~3,2%_%time:~6,2%.sql
set filename=%filename: =0%

set filename2=%dbvalue%_DB_%date:~6,4%%date:~3,2%%date:~0,2%_%time:~0,2%_%time:~3,2%_%time:~6,2%.sql
set filename2=%filename2: =0%

echo %filename%
echo %filename2%
echo %filename% | findstr /C:"/" > nul && (set filename=%filename2%) || (set filename=%filename%)

"C:\xampp\mysql\bin\mysqldump.exe" -u posme -proot1.2Blandon  -h localhost %dbvalue% > C:\TeamDS-Importacion\%filename%   -v --opt --events --routines --triggers --default-character-set=utf8 

echo %dbvalue%
echo %filename%
#comentario -------------
#pause comentado
#comentario -------------