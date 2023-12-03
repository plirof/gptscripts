#!/bin/bash

# Set the URL of the HTML page that contains the links to files
#URL="https://example.com/link_page.html"
URL="http://localhost/img/gptbashupdate/page.html"

# Set the local folder where you want to save the files
LOCAL_FOLDER="/opt/lampp/htdocs/img/gptbashupdate/local_folder/"

# Define an array of prefixes to check
PREFIXES=("google" "example")

# Download the HTML page containing the links
wget -O page.html "$URL"
echo $PREFIXES
# Check if the HTML page was downloaded successfully
if [ $? -eq 0 ]; then
    for prefix in "${PREFIXES[@]}"; do
        # List files in the local folder that start with the current prefix
        local_files=("$LOCAL_FOLDER"/"$prefix"*)
        echo "local_files"$local_files
        # Extract links to files with the same prefix from the HTML page
        links=($(grep -o -E "href=\"[^\"]+$prefix[^\"/]*" page.html | sed 's/href="//'))
         echo "links:"$links
        # Rename old files to "*_old" and download new files
        for local_file in "${local_files[@]}"; do
            filename=$(basename "$local_file")
            echo "filename:"$filename
            filenameRemote=$(basename "$links")
            echo "filenameRemote:"$filenameRemote
            if [[ "${links[*]}" == *"$prefix$filename"* ]]; then
                # File with the same name exists on the HTML page, consider it as new
                mv "$local_file" "${local_file}_old"
                wget -P "$LOCAL_FOLDER" "$URL$filename" -O "$filename"
            fi
        done
    done

    # Remove the downloaded HTML page
    rm page.html
else
    echo "Failed to download the HTML page."
fi