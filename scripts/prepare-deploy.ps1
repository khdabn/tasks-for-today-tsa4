$ErrorActionPreference = 'Stop'
$root = Split-Path $PSScriptRoot -Parent
Set-Location $root
if (!(Test-Path 'app/Http/Controllers/Auth/AuthenticatedSessionController.php')) {
    throw 'Run scripts/setup-local.ps1 first so the official Breeze scaffolding exists.'
}
if (!(Test-Path 'composer.lock')) { throw 'Run local setup first to generate composer.lock.' }
$stage = Join-Path $root 'deploy-output/htdocs'
if (Test-Path $stage) { throw 'deploy-output/htdocs already exists. Rename or remove deploy-output before preparing a new deployment.' }
New-Item -ItemType Directory -Path $stage -Force | Out-Null
foreach ($item in @('app','bootstrap','config','database','public','resources','routes','storage','artisan','composer.json','composer.lock')) {
    Copy-Item (Join-Path $root $item) -Destination $stage -Recurse -Force
}
# Remove local paths, session data and private logs from the staging copy only.
foreach ($relative in @('bootstrap/cache','storage/framework/views','storage/framework/sessions','storage/framework/cache/data','storage/logs')) {
    $folder = Join-Path $stage $relative
    Get-ChildItem -LiteralPath $folder -Force | Where-Object { $_.Name -ne '.gitignore' } | Remove-Item -Recurse -Force
}
Copy-Item (Join-Path $root 'deployment/infinityfree.htaccess') (Join-Path $stage '.htaccess') -Force
Push-Location $stage
try {
    & composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
    if ($LASTEXITCODE -ne 0) { throw 'Production dependency installation failed.' }
} finally { Pop-Location }
$zip = Join-Path $root 'deploy-output/TSA4-InfinityFree.zip'
# Explicit enumeration includes the root .htaccess and dotfiles.
Add-Type -AssemblyName System.IO.Compression.FileSystem
$archive = [IO.Compression.ZipFile]::Open($zip, [IO.Compression.ZipArchiveMode]::Create)
try {
    Get-ChildItem $stage -File -Recurse -Force | ForEach-Object {
        $entry = $_.FullName.Substring($stage.Length + 1).Replace('\','/')
        [IO.Compression.ZipFileExtensions]::CreateEntryFromFile($archive, $_.FullName, $entry) | Out-Null
    }
} finally { $archive.Dispose() }
Write-Host "Created $zip"
Write-Host 'The ZIP excludes .env, local logs, sessions, and tests. Upload a separately configured production .env.'
