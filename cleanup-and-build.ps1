Write-Host "Cleaning up Docker environment..." -ForegroundColor Yellow
docker-compose down -v
docker system prune -f

Write-Host "Removing node_modules directories..." -ForegroundColor Yellow
Remove-Item -Recurse -Force user-service\node_modules -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force sound-service\node_modules -ErrorAction SilentlyContinue

Write-Host "Cleaning npm cache..." -ForegroundColor Yellow
Push-Location user-service
npm cache clean --force
Pop-Location

Push-Location sound-service
npm cache clean --force
Pop-Location

Write-Host "Building Docker images..." -ForegroundColor Yellow
docker-compose build --no-cache

Write-Host "Starting services..." -ForegroundColor Green
docker-compose up -d

Write-Host "Build complete! Check status with: docker-compose ps" -ForegroundColor Green
