#ffmpeg -i "$1".mp4 -i "$1".srt -c:s mov_text -c:v copy -c:a copy "$1"GREnswm1.mp4
#ffmpeg -i "$1".mp4 -i "$1".srt -c:s mov_text -c:v copy -c:a copy -map 0 -map 1 -metadata:s:s:0 language=gr "$1"GREnswm2.mp4
#Method 3 Recompresses VIDEO
#ffmpeg -i "$1".mp4 -vf subtitles="$1".srt "$1"GREnswm3.mp4
ffmpeg -i "$1".mp4 -i "$1".srt -c copy -vf subtitles="$1".srt "$1"GREnswm3b.mp4