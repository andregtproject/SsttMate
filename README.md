# Sound Level Monitoring System

## Setup Instructions

### 1. Clone Repository
```bash
git clone <repository-url>
cd Projek-Sound-Level-monitoring
```

### 2. Setup Firebase Credentials
1. Copy your Firebase service account JSON file to:
   - `sound-service/config/firebase_credentials.json`
2. Or use the template:
   ```bash
   cp sound-service/config/firebase_credentials.json.example sound-service/config/firebase_credentials.json
   ```
3. Edit the file with your actual Firebase credentials

### 3. Build and Run
```bash
# Build containers
docker-compose build

# Start services
docker-compose up -d

# Check logs
docker logs user-service
docker logs sound-service
```

### 4. Access Services
- User Service: http://localhost:8000
- Sound Service: http://localhost:8001
- GraphQL Endpoint: http://localhost:8001/graphql.php
- Firebase Test: http://localhost:8001/test-firebase

## Important Security Notes

⚠️ **NEVER commit Firebase credentials to Git!**

- Firebase credentials are in `.gitignore`
- Use environment variables in production
- Keep credentials secure and rotate regularly
