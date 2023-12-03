@echo off

echo "set variables"
set dbvalue=biometric
set folder=C:\TeamDS-Importacion\
set lastfile=none



echo "folder"
echo %folder%



echo "escritura o busqueda"
dir /b %folder%%dbvalue%_DB_*.sql > temp.temp



echo "render ultimo archivo"
for /f %%i in (temp.temp) do (	
	set lastfile=%%i
)
echo %lastfile%



echo "borrado"
del temp.temp


echo "restaurar"
"C:\xampp\mysql\bin\mysql.exe" -u posme -proot1.2Blandon  -h localhost %dbvalue% < %folder%%lastfile%
 


echo "fin"
pause
