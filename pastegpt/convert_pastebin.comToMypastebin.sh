a.sh
javascript
#!/bin/bash

# Directory containing the files
directory="./"  # Modify this to the folder you want to scan

# Loop through all files in the directory
for file in "$directory"/*; do
  # Skip if it's not a file
  if [ -f "$file" ]; then
    # Get the filename without the path
    filename=$(basename "$file")
    
    # Insert the filename and the word "javascript" at the beginning of the file
    # This creates a temporary file with the new content and then replaces the original file
    {
      echo "$filename"
      echo "javascript"
      cat "$file"
    } > "$file.new" && mv "$file.new" "$file"
    
    echo "Modified: $file"
  fi
done
