#!/bin/bash
# Check current folder for videos and creates a clone of the source.srt file for each video

# Define the name of the source .srt file
source_srt="srt_clone.srt"

# Iterate over video files in the current folder
for video_file in *.mp4 *.mkv *.avi; do
    # Check if a corresponding .srt file exists
    if [[ ! -f "${video_file%.*}.srt" ]]; then
        # Copy the source .srt file to create a new .srt file for the video
        cp "$source_srt" "${video_file%.*}.srt"
        echo "Created ${video_file%.*}.srt"
    fi
done
