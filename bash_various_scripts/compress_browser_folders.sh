#!/bin/bash
#
# Compress/backup browser .data folders (this was used to compress my .data folders of my browsers)
#
# 231216 - v01 - ok seems to work version
#
#
#
#
#
#ChatGPT :
# I have a folder with some subfolders. I want a bash script to compress each subfolder in a different compressed file. File name of the compressed files should be {folder filename}_{current date in YYMMDD format}
#
#
#

# Get the current date in YYMMDD format
current_date=$(date +%y%m%d)

# Path to the main folder containing subfolders
main_folder="/mnt/home/downloads_linux/.data/"
main_folder="/mnt/home/downloads_linux/.data/"

# Loop through each subfolder
for folder in "${main_folder}"/*; do
    if [ -d "$folder" ]; then
        # Get the subfolder name without the path
        subfolder_name=$(basename "$folder")

        # Compress the subfolder into a separate compressed file
        tar -czvf "${subfolder_name}_${current_date}.tar.gz" -C "${main_folder}" "$subfolder_name"

        echo "Compressed $subfolder_name"
    fi
done