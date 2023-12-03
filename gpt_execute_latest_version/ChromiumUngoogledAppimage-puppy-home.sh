#!/bin/sh
# https://ungoogled-software.github.io/ungoogled-chromium-binaries/releases/appimage/64bit/
# https://ungoogled-software.github.io/ungoogled-chromium-binaries/releases/appimage/64bit/119.0.6045.199-1
xhost +local:puppy
#MYCHROMIUM='Chromium_Web_Browser-119.0.6045.199-1.deb11u1-x86_64.AppImage'
MYCHROMIUM='ChromiumUngoogledAppimage_findlatest.sh'
#MYPATH=/appimages/Internet/
MYPATH=''
MYPREFIX=${MYCHROMIUM:0:18}

mkdir -p /mnt/home/downloads_linux/.data/"$MYPREFIX"
mkdir -p /mnt/home/downloads_linux/.cache/"$MYPREFIX"
#sudo -u puppy google-chrome-stable --user-data-dir=/mnt/home/downloads_linux/.data/google-chrome-stable --disk-cache-dir=/mnt/home/downloads_linux/.cache/google-chrome-stable --ppapi-flash-path=/usr/lib/adobe-flashplugin/libpepflashplayer.so --disable-features=TranslateUI --always-authorize-plugins  --ppapi-flash-version=31.0.0.171 "$@"
sudo -u puppy $MYPATH$MYCHROMIUM --user-data-dir=/mnt/home/downloads_linux/.data/"$MYPREFIX" --disk-cache-dir=/mnt/home/downloads_linux/.cache/"$MYPREFIX" --always-authorize-plugins --simulate-outdated-no-au=407466480500 --no-default-browser-check  "$@"
