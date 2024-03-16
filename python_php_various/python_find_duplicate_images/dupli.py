#I want to make a python 3 script. I want to scan images and find which of them are at least 80% identical . As outout mI want it to create a file named duplicates.sh that contains in each line a bash command to move the duplicate image to a folder named DUPLICATES.

import os
import shutil
from PIL import Image
from imagehash import phash

# Directory containing the images
image_dir = '<path_to_images_directory>'

# Create the DUPLICATES folder if it doesn't exist
duplicates_dir = os.path.join(image_dir, 'DUPLICATES')
os.makedirs(duplicates_dir, exist_ok=True)

# Dictionary to store image hashes
image_hashes = {}

# Iterate through all the images in the directory
for filename in os.listdir(image_dir):
    if filename.endswith('.jpg') or filename.endswith('.png'):
        filepath = os.path.join(image_dir, filename)
        
        # Open the image and calculate its perceptual hash
        with Image.open(filepath) as img:
            hash_value = phash(img)
        
        # Check if the hash already exists
        if hash_value in image_hashes:
            duplicate_filename = image_hashes[hash_value]
            
            # Create the bash command to move the duplicate image to DUPLICATES folder
            bash_command = f'mv "{filepath}" "{os.path.join(duplicates_dir, duplicate_filename)}"'
            
            # Append the bash command to duplicates.sh
            with open('duplicates.sh', 'a') as duplicates_file:
                duplicates_file.write(bash_command + '\n')
        else:
            # Store the hash if it doesn't exist in the dictionary
            image_hashes[hash_value] = filename

print('Duplicates.sh file created successfully.')
