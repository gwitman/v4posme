@echo off
::mediante chrome
::start chrome "http://localhost/posmev4/core_upgrade/index"  --incognito

::mediante curl en .bat
::curl http://localhost/posmev4/core_upgrade/byWebServer

::mediante curl en .bat
::2026-11-10 retirado: core_upgrade/byPop3 ya no actualiza por correo (IMAP).
::La actualizacion ahora es manual desde la pantalla core_upgrade/byPop3 (requiere usuario administrador).
::curl http://localhost/posmev4/core_upgrade/byPop3