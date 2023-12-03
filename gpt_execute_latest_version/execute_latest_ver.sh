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
  version=$(echo "$file" | grep -oP "(?<=${prefix})[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+" | head -n 1)
  if [ -n "$version" ]; then
    echo "Detected version number: $version"
    IFS='.' read -ra version_parts <<< "$version"
    if [ -z "$latest_version" ] || [ "${version_parts[0]}" -gt "${latest_version_parts[0]}" ] || [ "${version_parts[1]}" -gt "${latest_version_parts[1]}" ] || [ "${version_parts[2]}" -gt "${latest_version_parts[2]}" ] || [ "${version_parts[3]}" -gt "${latest_version_parts[3]}" ]; then
      latest_version="$version"
      latest_version_parts=("${version_parts[@]}")
      latest_file="$file"
    fi
  fi
done


# Execute the file with the latest version
#echo "Executing the latest version: $latest_file"
echo $latest_file
echo $latest_version


# Execute the file with the latest version
if [ -n "$latest_file" ]; then
  echo "Executing the latest version: $latest_file"
  chmod +x "$latest_file"
  ./"$latest_file"
else
  echo "No valid version number detected in any file."
fi
