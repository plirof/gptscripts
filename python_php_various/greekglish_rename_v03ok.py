import os
#
# Converts filenames wuth Greek characters in current folder to Greekglish filenames.
# v03 - 231216 - seems to work
#
#
#
def custom_unidecode(char):
    # Προσαρμογή της unidecode για ειδικές αντιστοιχίες
    from_chars = ["ου", "ΟΥ", "Ού", "ού", "αυ", "ΑΥ", "Αύ", "αύ", "ευ", "ΕΥ", "Εύ", "εύ", "α", "Α", "ά", "Ά", "β", "Β", "γ", "Γ", "δ", "Δ", "ε", "Ε", "έ", "Έ", "ζ", "Ζ", "η", "Η", "ή", "Ή", "θ", "Θ", "ι", "Ι", "ί", "Ί", "ϊ", "ΐ", "Ϊ", "κ", "Κ", "λ", "Λ", "μ", "Μ", "ν", "Ν", "ξ", "Ξ", "ο", "Ο", "ό", "Ό", "π", "Π", "ρ", "Ρ", "σ", "Σ", "ς", "τ", "Τ", "υ", "Υ", "ύ", "Ύ", "ϋ", "ΰ", "Ϋ", "φ", "Φ", "χ", "Χ", "ψ", "Ψ", "ω", "Ω", "ώ", "Ώ"]
    to_chars = ["ou", "ou", "ou", "ou", "au", "au", "au", "au", "eu", "eu", "eu", "eu", "a", "a", "a", "a", "b", "b", "g", "g", "d", "d", "e", "e", "e", "e", "z", "z", "i", "i", "i", "i", "th", "th", "i", "i", "i", "i", "i", "i", "i", "k", "k", "l", "l", "m", "m", "n", "n", "ks", "ks", "o", "o", "o", "o", "p", "p", "r", "r", "s", "s", "s", "t", "t", "y", "y", "y", "y", "y", "y", "y", "f", "f", "x", "x", "ps", "ps", "o", "o", "o", "o"]

    # Αντικατάσταση ειδικών χαρακτήρων
    replacements = dict(zip(from_chars, to_chars))
    return replacements.get(char, char)

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
