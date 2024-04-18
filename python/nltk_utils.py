import numpy as np
import nltk
#kung di pa installed yung nasa baba paki tanggal nalang yung comment
# nltk.download('punkt')
from nltk.stem.porter import PorterStemmer
stemmer = PorterStemmer()


def tokenize(sentence):
    """
    Customized tokenization to handle contractions and punctuation better
    """
    # Custom handling of contractions - you can expand this as needed
    contractions = {
        "n't": "not",
        "'s": "is",
        "'ll": "will",
        "'ve": "have",
        "'re": "are",
        "'d": "would",
        "'m": "am"
    }
    
    # Replace contractions with their expanded forms
    for contraction, expansion in contractions.items():
        sentence = sentence.replace(contraction, expansion)

    # Use NLTK's tokenizer for word splitting
    tokens = nltk.word_tokenize(sentence)
    return tokens



def stem(word):
    """
    stemming = find the root form of the word
    examples:
    words = ["organize", "organizes", "organizing"]
    words = [stem(w) for w in words]
    -> ["organ", "organ", "organ"]
    """
    return stemmer.stem(word.lower())


def bag_of_words(tokenized_sentence, words):
    """
    return bag of words array:
    1 for each known word that exists in the sentence, 0 otherwise
    example:
    sentence = ["hello", "how", "are", "you"]
    words = ["hi", "hello", "I", "you", "bye", "thank", "cool"]
    bog   = [  0 ,    1 ,    0 ,   1 ,    0 ,    0 ,      0]
    """
    # stem each word
    sentence_words = [stem(word) for word in tokenized_sentence]
    # initialize bag with 0 for each word
    bag = np.zeros(len(words), dtype=np.float32)
    for idx, w in enumerate(words):
        if w in sentence_words: 
            bag[idx] = 1

    return bag
