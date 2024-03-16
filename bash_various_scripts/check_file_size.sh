#!/bin/bash
# Check files sizes between two folders and reports the files with the same size
# v001older - Check two 
# dir1 is target
dir1="/tmp/incom_encr/mp4/"
dir2="/media/veracrypt1/incom_encr/PrimaryFolder--/"
#dir2="/tmp/incom_encr/jas/"

echo "Indexing $dir2 ... Please wait ..."

find "$dir2" -type f -follow -exec ls -l {} \; > "dir2_ls_file.txt"

while read f1 f2 f3 f4 f5 f6 f7 f8 f9
                do
                        size="${f5}"
                        name="${f9}"
                        result0=$(find "$dir1" -type f -size "$size"c -follow)
                        result1=$(echo "$result0" | wc -l)
                        result2=$(find "$dir2" -type f -size "$size"c -follow | wc -l)
                                if [ $result2 -gt 1 ]; then
                                        echo "There is more than one file under $dir2 with the same size as $name , so no action is taken!"
                                elif [ $result1 -eq 1 ] && [ "$result0" ]; then
                                        echo "$result0 is the same size as $name , so it will be replaced."
                                       #uncommment to copy ORIGINAL file with cloned one
                                       #cp "$name" $dir1 
                                else
                                        echo "There is more than one file or no file under $dir1 with the same size as $name , so no action is taken!"
                                fi
                done < "dir2_ls_file.txt"