@echo off

echo "Nueva password: root1.2Blandon"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'root1.2Blandon';"

"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE USER 'root'@'127.0.0.1' IDENTIFIED BY 'root1.2Blandon';"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"

"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE USER 'root'@'%' IDENTIFIED BY 'root1.2Blandon';"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"

"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE USER 'root'@'::1' IDENTIFIED BY 'root1.2Blandon';"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'::1' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"


"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE USER 'posme'@'localhost' IDENTIFIED BY 'root1.2Blandon';"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "GRANT ALL PRIVILEGES ON *.* TO 'posme'@'localhost' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"

"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE USER 'posme'@'127.0.0.1' IDENTIFIED BY 'root1.2Blandon';"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "GRANT ALL PRIVILEGES ON *.* TO 'posme'@'127.0.0.1' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"

"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE USER 'posme'@'%' IDENTIFIED BY 'root1.2Blandon';"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "GRANT ALL PRIVILEGES ON *.* TO 'posme'@'%' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"

"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE USER 'posme'@'::1' IDENTIFIED BY 'root1.2Blandon';"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "GRANT ALL PRIVILEGES ON *.* TO 'posme'@'::1' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"


"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE DATABASE posme;"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE DATABASE posme_posme;"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE DATABASE posme_fidlocal;"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE DATABASE posme_merge;"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE DATABASE posme_biometric;"
"C:\xampp\mysql\bin\mysql.exe" -u root -p -h localhost -e "CREATE DATABASE biometric;"