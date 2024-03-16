# need : pip install unidecode
import os
from unidecode import unidecode

def custom_unidecode(char):
    # Προσαρμογή της unidecode για ειδικές αντιστοιχίες
    replacements = {'η': 'i','ή': 'i', 'ί': 'i', 'Η': 'I', 'Ί': 'I', 'ι': 'i', 'ί': 'i', 'Ι': 'I', 'Ί': 'I'}
    return replacements.get(char, unidecode(char))

def rename_files():
    current_directory = os.getcwd()
    
    for filename in os.listdir(current_directory):
        if os.path.isfile(os.path.join(current_directory, filename)):
            # Αντικατάσταση ελληνικών χαρακτήρων με greekglish
            new_filename = ''.join(custom_unidecode(char) for char in filename)
            
            # Μετονομασία του αρχείου
            os.rename(os.path.join(current_directory, filename), os.path.join(current_directory, new_filename))
            
            print(f'Renamed: {filename} -> {new_filename}')

if __name__ == "__main__":
    rename_files()
