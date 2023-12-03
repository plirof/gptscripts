# Description
we have a local and a remote folder (in LAN). We run a script in local folder that checks the version od a file eg "google-chrome-stable_117.0.5938_amd64.deb_auto_v01_230930.txt" . We next scan a file with links in the remote folder and find the files with the same prefix (google-chrome-stable). Then we compare the remote with the local versions. If local_ver<remote_ver then download and replace local file


# Changes:
-231031 ; Pre-alpha ,seem to work version