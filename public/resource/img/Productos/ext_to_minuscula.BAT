@echo off
setlocal enabledelayedexpansion

for %%F in (*) do (
    set "filename=%%~nF"
    set "extension=%%~xF"
    set "extension=!extension:~1!"
    set "lower_extension=!extension!"

    for %%A in (A B C D E F G H I J K L M N O P Q R S T U V W X Y Z) do (
        set "lower_extension=!lower_extension:%%A=%%A!"
    )

    ren "%%F" "!filename!.!lower_extension!"
)

endlocal
