#!/bin/bash

# Set the URL of the HTML page that contains the links to files
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
        echo "local_files: ${local_files[@]}"
        
        # Extract links to files with the same prefix from the HTML page
        links=($(grep -o -E "href=\"[^\"]+$prefix[^\"/]*" page.html | sed 's/href="//'))
        echo "links: ${links[@]}"
        
        # Compare and update files based on alphanumeric order
        for local_file in "${local_files[@]}"; do
            filename=$(basename "$local_file")
            echo "filename: $filename"
            for link in "${links[@]}"; do
                filenameRemote=$(basename "$link")
                echo "filenameRemote: $filenameRemote"
                
                # Check if $filenameRemote starts with $prefix
                if [[ "$filenameRemote" == "$prefix"* ]]; then
                    # Extract the part of the filename after the prefix
                    filenameRemoteSuffix="${filenameRemote#"$prefix"}"
                    echo "filenameRemoteSuffix: $filenameRemoteSuffix"
                    # Extract the part of the filename after the prefix from the local file
                    filenameLocalSuffix="${filename#"$prefix"}"
                    echo "filenameLocalSuffix: $filenameLocalSuffix"

                    # Compare filenames based on alphanumeric order
                    if [[ "$filenameRemoteSuffix" > "$filenameLocalSuffix" ]]; then
                        echo "_______filenameRemoteSuffix < filenameLocalSuffix_______"
                        mv "$local_file" "${local_file}_old"
                        wget -P "$LOCAL_FOLDER" "$URL$filenameRemote" -O "$filenameRemote"
                    fi
                    echo "___________________________________"
                fi
            done
        done
    done

    # Remove the downloaded HTML page
    rm page.html
else
    echo "Failed to download the HTML page."
fi
