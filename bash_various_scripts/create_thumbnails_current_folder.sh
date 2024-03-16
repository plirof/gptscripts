#
# v240105 - Creates thumbnail of the videos inside the specifies folder . THumbnail taken from the 10th second of the each video
#for f in "*.mp4"; do ffmpeg -i "$f" -ss 00:00:10 -vframes 1  "${f%.mp4}.jpg"; done


for f in /path/to/folder/*.{mp4,avi,flv}; do 
  ffmpeg -i "$f" -ss 00:00:10 -vframes 1 "${f%.*}.jpg"
done