#!/bin/bash

# GitHub repository owner and name
OWNER="ivan-hc"
REPO="Chromium-Web-Browser-appimage"

# Fetch the latest release information using the GitHub API
API_URL="https://api.github.com/repos/$OWNER/$REPO/releases/latest"
RELEASE_INFO=$(curl -s "$API_URL")

# Check if the request was successful
if [[ $RELEASE_INFO == *"Not Found"* ]]; then
    echo "Error: Repository or release not found. Please check the repository owner and name."
    exit 1
fi

# Extract the latest release version and asset download URL using php
LATEST_VERSION=$(php -r 'echo json_decode(file_get_contents("php://stdin"))->tag_name;' <<< "$RELEASE_INFO")
DOWNLOAD_URL=$(php -r 'echo json_decode(file_get_contents("php://stdin"))->assets[0]->browser_download_url;' <<< "$RELEASE_INFO")

# Download the latest version
wget "$DOWNLOAD_URL" -O "Chromium_Web_Browser-$LATEST_VERSION.AppImage"

echo "Downloaded Chromium Web Browser version $LATEST_VERSION to Chromium_Web_Browser-$LATEST_VERSION.AppImage"
