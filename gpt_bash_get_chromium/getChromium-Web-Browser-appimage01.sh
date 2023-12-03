#!/bin/bash

# URL of the GitHub releases page
RELEASES_URL="https://github.com/ivan-hc/Chromium-Web-Browser-appimage/releases"

# Fetch the HTML content of the releases page
#HTML_CONTENT=$(curl -s "$RELEASES_URL")
HTML_CONTENT=$(curl -s -L -A "Mozilla/5.0" "$RELEASES_URL")
echo "$HTML_CONTENT" > file.html
# Extract the download link for the latest version
LATEST_VERSION_LINK=$(echo "$HTML_CONTENT" | grep -o -m 1 -E "/ivan-hc/Chromium-Web-Browser-appimage/releases/download/continuous/Chromium_Web_Browser-[0-9.]+-1\.deb11u1-x86_64\.AppImage")

# Check if the link is found
if [ -n "$LATEST_VERSION_LINK" ]; then
    # Extract the version from the link
    LATEST_VERSION=$(echo "$LATEST_VERSION_LINK" | grep -o -E "[0-9.]+")

    # Construct the final download link
    DOWNLOAD_LINK="${LATEST_VERSION_LINK//$LATEST_VERSION/latest}"

    # Download the latest version
    wget "$DOWNLOAD_LINK" -O Chromium_Web_Browser-latest.AppImage

    echo "Downloaded Chromium Web Browser version $LATEST_VERSION to Chromium_Web_Browser-latest.AppImage"
else
    echo "Failed to find the download link for the latest version."
fi
