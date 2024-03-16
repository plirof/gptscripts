#reduce video size (doesn't always work)
ffmpeg -i "$1" -c:v libx264 -preset fast -crf 23 -c:a copy "$1"_crf23.mp4