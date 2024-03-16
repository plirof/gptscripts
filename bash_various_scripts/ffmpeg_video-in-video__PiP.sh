

#https://superuser.com/questions/1721180/ffmpeg-insert-video-on-top-of-other-video
#ffmpeg -i "$1" -i "$2" -filter_complex "[1:v]scale=500:-1[v2];[0:v][v2]overlay=main_w-overlay_w-5:5" -c:v libx264 -c:a copy "output2.mp4"
ffmpeg -i "$1" -i "$2" -filter_complex "[1:v]scale=100:-1[v2];[0:v][v2]overlay=main_w-overlay_w-5:5" -c:v libx264 -c:a copy "output2.mp4"