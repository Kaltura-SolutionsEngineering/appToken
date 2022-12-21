# App Token session

Script to create an AppToken-based Kaltura session (ks).

# Prerequisites
Correctly configured AppToken. See also https://developer.kaltura.com/api-docs/VPaaS-API-Getting-Started/application-tokens.html and https://developer.kaltura.com/workflows/Manage_and_Deliver_Apps_and_Widgets/App_Token_Authentication

# Usage
Minimum PHP version 5.6

## Steps
1. In startAppTokenSession.php, adjust path to Kaltura PHP client
2. In config.ini, enter PID, AppToken Id, and token value 
3. Open the Terminal or similar
4. Run following command:
		`php startAppTokenSession.php`
5. The script writes each steps it takes to console output, including the KS it creates

