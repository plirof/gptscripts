#!/bin/sh
# https://github.com/ivan-hc/Chromium-Web-Browser-appimage/releases
#export CHROMIUM_FLAGS="--ppapi-flash-path=/usr/lib/adobe-flashplugin/libpepflashplayer.so --ppapi-flash-version=26.0.0.137"
#export google-chrome-stable_FLAGS="--ppapi-flash-path=/usr/lib/adobe-flashplugin/libpepflashplayer.so  --ppapi-flash-version=29.0.0.171  --media-cache-size=10000000"
xhost +local:puppy
#sudo chown root /opt/google/chrome/chrome-sandbox
#sudo chmod 4755 /opt/google/chrome/chrome-sandbox
MYCHROMIUM='Chromium_Web_Browser-119.0.6045.199-1.deb11u1-x86_64.AppImage'
MYPATH=/appimages/Internet/
MYPREFIX=${MYCHROMIUM:0:12}
#sudo -u puppy $MYPATH$MYCHROMIUM --user-data-dir=/home/puppy/.data/"$MYPREFIX"_puppy_user_data_dir --disk-cache-dir=/home/puppy/.cache/"$MYPREFIX"_puppy_user_cache_dir --disable-features=TranslateUI --always-authorize-plugins --media-cache-size=10000000 --simulate-outdated-no-au=407466480500  --no-default-browser-check "$@"
sudo -u puppy $MYPATH$MYCHROMIUM --user-data-dir=/home/puppy/.data/"$MYPREFIX"_puppy_user_data_dir --disk-cache-dir=/home/puppy/.cache/"$MYPREFIX"_puppy_user_cache_dir --always-authorize-plugins --media-cache-size=10000000 --simulate-outdated-no-au=407466480500 --no-default-browser-check "$@"
