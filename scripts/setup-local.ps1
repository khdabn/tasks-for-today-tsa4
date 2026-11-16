$ErrorActionPreference = 'Stop'
Set-Location (Split-Path $PSScriptRoot -Parent)
function Run-Checked {
    param([string]$Program, [string[]]$Arguments)
    & $Program @Arguments
    if ($LASTEXITCODE -ne 0) { throw "$Program failed. Read the output above, fix the issue, and rerun the script." }
}
if (!(Get-Command php -ErrorAction SilentlyContinue)) { throw 'PHP is not in PATH.' }
if (!(Get-Command composer -ErrorAction SilentlyContinue)) { throw 'Composer is not in PATH.' }
if (!(Test-Path '.env')) {
    Copy-Item '.env.example' '.env'
    $secret = Read-Host 'Choose a unique manager password (12+ characters)' -AsSecureString
    $ptr = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($secret)
    try { $password = [Runtime.InteropServices.Marshal]::PtrToStringBSTR($ptr) }
    finally { [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($ptr) }
    if ($password.Length -lt 12) { Remove-Item '.env'; throw 'Password must contain at least 12 characters. Rerun setup.' }
    # A quoted dotenv value must escape backslashes, quotes and interpolation dollars.
    $escaped = $password.Replace('\','\\').Replace('"','\"').Replace('$','\$')
    $content = [IO.File]::ReadAllText((Join-Path $PWD '.env'))
    $content = $content.Replace('ADMIN_PASSWORD=',('ADMIN_PASSWORD="' + $escaped + '"'))
    [IO.File]::WriteAllText((Join-Path $PWD '.env'), $content, (New-Object Text.UTF8Encoding($false)))
    $password = $null; $escaped = $null
    Write-Host 'Created .env. Review its database settings before continuing.'
}
Run-Checked -Program 'composer' -Arguments @('install','--no-interaction','--prefer-dist')
Run-Checked -Program 'php' -Arguments @('scripts/install-breeze.php')
$envText = [IO.File]::ReadAllText((Join-Path $PWD '.env'))
if ($envText -match '(?m)^APP_KEY=\s*$') { Run-Checked -Program 'php' -Arguments @('artisan','key:generate') }
Run-Checked -Program 'php' -Arguments @('artisan','config:clear')
Write-Host ''
Write-Host 'Setup finished. Create the EMPTY MySQL database tasks_for_today_tsa4 in phpMyAdmin.'
Write-Host 'Then run: php artisan migrate --seed'
Write-Host 'Then run: php artisan test'
Write-Host 'Then run: php artisan serve'
Write-Host 'Log in with ADMIN_EMAIL and the password you chose. No npm build is required.'
