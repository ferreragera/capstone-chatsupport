from gevent.pywsgi import WSGIServer
from flask import Flask, render_template, request, jsonify
from flask_cors import CORS
from markupsafe import escape
import subprocess
import mysql.connector
import json
import random
from better_profanity import profanity
from difflib import SequenceMatcher
from flask import send_file
from flask import Flask, jsonify, request
import json
from fuzzywuzzy import process


conn = mysql.connector.connect(
    host='127.0.0.1',
    user='root',
    password='',
    database='capstone-chatsupport'
)

cursor = conn.cursor()
app = Flask(__name__)

CORS(app, resources={r"/train": {"origins": "*"}}) 

with open('intents.json', 'r') as file:
    intents = json.load(file)

@app.get("/")
def index_get():
    return render_template("index.php")

def get_response(user_input):
    best_match = None
    best_score = 0
    threshold = 0.6

    if len(user_input.split()) == 1:
        threshold = 0.4

    for intent in intents['intents']:
        for pattern in intent['patterns']:
            score = SequenceMatcher(None, user_input, pattern).ratio()
            if score > best_score and score > threshold:
                best_match = intent
                best_score = score

    if best_match:
        responses = best_match['responses']
        return [random.choice(responses)] 
    else:
        return ["I'm sorry, I don't understand that."]

@app.route('/predict', methods=['POST'])
def predict():
    user_input = request.json.get('message')

    # Check for profanity
    if contains_profanity(user_input):
        return jsonify({'response': ["I'm sorry, but I cannot respond to profane language."]})

    response = get_response(user_input)
    return jsonify({'response': response})


def contains_profanity(text):
    # Load profanity from better_profanity library
    profanity.load_censor_words()
    if profanity.contains_profanity(text):
        return True
    
    # Load profanity from profanity_wordlist.txt file
    with open('profanity_wordlist.txt', 'r') as file:
        profanity_list = [line.strip() for line in file]

    for word in profanity_list:
        if word.lower() in text.lower():
            return True

    return False


from flask import request

@app.route('/train', methods=['POST'])
def train_chatbot():
    subprocess.run(['python', 'train.py'])
    return 'Training started'

def contains_profanity(text):
    return profanity.contains_profanity(text)

from datetime import datetime

@app.route('/save_rating', methods=['POST'])
def save_rating():
    rating = request.form["rating"]
    current_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

    try:
        insert_query = "INSERT INTO ratings (rating_value, created_at, updated_at) VALUES (%s, %s, %s)"
        cursor.execute(insert_query, (rating, current_time, current_time))
        conn.commit()
        return '''
            <script>
                alert("Thanks for the rating!");
                window.location.href = '/';
            </script>
        '''
    except Exception as e:
        return f"An error occurred: {str(e)}"

@app.route('/save_feedback', methods=['POST'])
def save_feedback():
    feedback = request.form["feedback"]
    current_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    try:
        insert_query = "INSERT INTO feedback (feedback, created_at, updated_at) VALUES (%s, %s, %s)"
        cursor.execute(insert_query, (feedback, current_time, current_time))
        conn.commit()
        return '''
            <script>
                alert("Thanks for the feedback!");
                window.location.href = '/';
            </script>
        '''
    except Exception as e:
        return f"An error occurred: {str(e)}"
    
@app.route('/test_intents', methods=['GET'])
def test_intents():
    return jsonify(intents)

@app.route('/get_intents', methods=['GET'])
def get_intents():
    with open('intents.json', 'r') as file:
        intents = json.load(file)
    return jsonify(intents)

@app.route('/match_pattern', methods=['POST'])
def match_pattern():
    user_input = request.json['userInput']
    with open('intents.json', 'r') as file:
        intents = json.load(file)
    
    all_patterns = [(pattern, intent['tag'], intent['responses']) for intent in intents['intents'] for pattern in intent['patterns']]
    patterns, tags, responses = zip(*all_patterns)
    
    # Use fuzzy matching to find the best matches
    matches = process.extract(user_input, patterns, limit=5)
    
    matched_patterns = []
    for match in matches:
        best_match = match[0]
        score = match[1]
        matched_tag = tags[patterns.index(best_match)]
        matched_response = random.choice(responses[patterns.index(best_match)])
        matched_patterns.append({
            'pattern': best_match,
            'tag': matched_tag,
            'response': matched_response,
            'score': score
        })
    
    return jsonify(matched_patterns)



if __name__ == "__main__":
    http_server = WSGIServer(('0.0.0.0', 5000), app)
    http_server.serve_forever()