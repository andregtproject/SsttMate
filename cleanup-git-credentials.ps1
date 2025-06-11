Write-Host "🔐 Cleaning up Firebase credentials from Git repository..." -ForegroundColor Yellow

# Stop on any error
$ErrorActionPreference = "Stop"

try {
    # 1. Remove files from working directory
    Write-Host "Removing credential files from working directory..." -ForegroundColor Cyan
    Remove-Item "sound-service\config\firebase_credentials.json" -ErrorAction SilentlyContinue
    Remove-Item "sound-service\config\firebase_credentials.json.php" -ErrorAction SilentlyContinue
    
    # 2. Remove from Git index
    Write-Host "Removing files from Git index..." -ForegroundColor Cyan
    git rm --cached "sound-service/config/firebase_credentials.json" 2>$null
    git rm --cached "sound-service/config/firebase_credentials.json.php" 2>$null
    
    # 3. Update .gitignore
    Write-Host "Updating .gitignore..." -ForegroundColor Cyan
    $gitignoreContent = @"
/node_modules
/public/build
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
/.fleet
/.idea
/.vscode

# Firebase credentials - NEVER COMMIT THESE!
firebase_credentials.json
**/firebase_credentials.json
firebase_credentials.json.php
**/firebase_credentials.json.php
sound-service/config/firebase_credentials.*
user-service/config/firebase_credentials.*
**/*.pem
**/*.p12
**/*.key
**/secrets/
**/*credentials*
**/*private_key*

# Environment files that might contain secrets
.env*
!.env.example
"@
    
    $gitignoreContent | Out-File -FilePath ".gitignore" -Encoding UTF8
    
    # 4. Clean Git history
    Write-Host "Cleaning Git history (this may take a while)..." -ForegroundColor Cyan
    git filter-branch --force --index-filter 'git rm --cached --ignore-unmatch "sound-service/config/firebase_credentials.json"; git rm --cached --ignore-unmatch "sound-service/config/firebase_credentials.json.php"' --prune-empty --tag-name-filter cat -- --all
    
    # 5. Remove backup refs
    Write-Host "Removing backup references..." -ForegroundColor Cyan
    git for-each-ref --format="%(refname)" refs/original/ | ForEach-Object { 
        git update-ref -d $_
    }
    
    # 6. Aggressive cleanup
    Write-Host "Performing aggressive Git cleanup..." -ForegroundColor Cyan
    git reflog expire --expire=now --all
    git gc --prune=now --aggressive
    
    # 7. Add and commit changes
    Write-Host "Committing security changes..." -ForegroundColor Cyan
    git add .gitignore
    git commit -m "SECURITY: Remove Firebase credentials completely

- Added comprehensive .gitignore for all credential files
- Removed firebase_credentials.json from repository  
- All Firebase auth now uses environment variables only
- This ensures no sensitive data is stored in repository"
    
    # 8. Create new clean branch
    $branchName = "DhaifanAzhar_production_secure_$(Get-Date -Format 'yyyyMMdd_HHmmss')"
    Write-Host "Creating clean branch: $branchName" -ForegroundColor Cyan
    git checkout -b $branchName
    
    # 9. Force push
    Write-Host "Force pushing clean branch..." -ForegroundColor Cyan
    git push origin -f $branchName
    
    Write-Host "✅ Repository cleanup completed successfully!" -ForegroundColor Green
    Write-Host "New clean branch created: $branchName" -ForegroundColor Green
    Write-Host "⚠️  IMPORTANT: You MUST regenerate Firebase credentials in Firebase Console!" -ForegroundColor Red
    
} catch {
    Write-Host "❌ Error during cleanup: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}
