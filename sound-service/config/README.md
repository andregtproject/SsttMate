# Firebase Configuration

## Setup Instructions

### Option 1: Using Environment Variables (Recommended)
All Firebase credentials are configured in the `.env` file. No additional setup needed.

### Option 2: Using Credentials File (Local Development)
1. Copy `firebase_credentials.json.example` to `firebase_credentials.json`
2. Replace all placeholder values with your actual Firebase credentials
3. **IMPORTANT**: Never commit `firebase_credentials.json` to Git!

## Security Notes
- The `firebase_credentials.json` file is automatically ignored by Git
- All production deployments should use environment variables
- Never share or commit actual credentials

## Getting Firebase Credentials
1. Go to Firebase Console
2. Navigate to Project Settings > Service Accounts
3. Click "Generate new private key"
4. Download the JSON file and use its contents
