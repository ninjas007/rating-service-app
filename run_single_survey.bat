@echo off
setlocal

rem === Ganti URL sesuai kebutuhan ===
set "URL=http://localhost/survey/preview/1"

rem === Path default Chrome (ubah kalau perlu) ===
set "CHROME_PATH=%ProgramFiles%\Google\Chrome\Application\chrome.exe"
if not exist "%CHROME_PATH%" set "CHROME_PATH=%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe"

rem === Jalankan Chrome dengan opsi kiosk dan optimasi ===
start "" "%CHROME_PATH%" ^
  --disable-pinch ^
  --incognito ^
  --noerrdialogs ^
  --disable-translate ^
  --no-first-run ^
  --fast ^
  --fast-start ^
  --disable-infobars ^
  --disable-features=TranslateUI ^
  --kiosk "%URL%"

endlocal
exit
