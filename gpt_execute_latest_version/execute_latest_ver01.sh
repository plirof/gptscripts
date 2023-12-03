#!/bin/bash

folder_path="/tmp/test"
prefix="Chromium_Web_Browser-"
extension=".AppImage"

# Navigate to the specified folder
cd "$folder_path" || exit

# Find files with the specified prefix and extension
files=($(find . -type f -name "$prefix*$extension"))

# If no matching files are found, exit
if [ ${#files[@]} -eq 0 ]; then
  echo "No matching files found."
  exit
fi

# Extract version numbers and find the file with the highest version
latest_version=""
latest_file=""
for file in "${files[@]}"; do
  version=$(echo "$file" | grep -oP "(?<=${prefix})[0-9.]+(?=${extension})")
  if [ -z "$latest_version" ] || [ "$(printf '%s\n' "$latest_version" "$version" | sort -V | head -n1)" == "$version" ]; then
    latest_version="$version"
    latest_file="$file"
  fi
done

# Execute the file with the latest version
echo "Executing the latest version: $latest_file"
echo $latest_file
echo $latest_version
#chmod +x "$latest_file"
#./"$latest_file"
