$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot
$phpRuntime = Join-Path $env:USERPROFILE '.codex\runtimes\php84\php.exe'
if (-not (Test-Path $phpRuntime)) { $phpRuntime = 'php' }
& $phpRuntime spark serve --host localhost --port 8085
