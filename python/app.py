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
from flask import Flask, jsonify, request
import threading
from fuzzywuzzy import process, fuzz
from nltk_utils import tokenize

conn = mysql.connector.connect(
    host='127.0.0.1',
    user='root',
    password='',
    database='capstone-chatsupport'
)

cursor = conn.cursor()
app = Flask(__name__)

# Enable CORS for all routes
CORS(app)
CORS(app, resources={r"/train": {"origins": "*"}})

with open('intents.json', 'r') as file:
    intents = json.load(file)

@app.get("/")
def index_get():
    return render_template("index.php")

def get_response(user_input):
    best_match = None
    best_score = 0
    threshold = 0.4  # Adjusted threshold for better matching

    # Tokenize the input
    user_input_words = set(tokenize(user_input.lower()))

    for intent in intents['intents']:
        for pattern in intent['patterns']:
            # Tokenize the pattern
            pattern_words = set(tokenize(pattern.lower()))
            
            # Calculate word match score
            word_match_score = len(user_input_words & pattern_words) / len(pattern_words)
            
            # Calculate fuzzy match score
            fuzzy_score = fuzz.partial_ratio(user_input, pattern)
            
            # Combine scores (you can adjust the weights as needed)
            combined_score = (0.5 * word_match_score) + (0.5 * (fuzzy_score / 100))
            
            if combined_score > best_score and combined_score > threshold:
                best_match = intent
                best_score = combined_score

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
    profanity.load_censor_words()
    if profanity.contains_profanity(text):
        return True
    
    with open('profanity_wordlist.txt', 'r') as file:
        profanity_list = [line.strip() for line in file]

    for word in profanity_list:
        if word.lower() in text.lower():
            return True

    return False

training_status = {"status": "idle"}

def train_chatbot():
    global training_status
    training_status["status"] = "training"
    try:
        subprocess.run(['python', 'train.py'])
        training_status["status"] = "completed"
    except Exception as e:
        training_status["status"] = f"error: {e}"

@app.route('/train', methods=['POST'])
def start_training():
    global training_status
    if training_status["status"] == "training":
        return 'Training already in progress', 409

    training_thread = threading.Thread(target=train_chatbot)
    training_thread.start()
    return 'Training started', 200

@app.route('/training_status', methods=['GET'])  # Renamed this endpoint
def get_training_status():
    return jsonify(training_status)

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

# Status of the chatbot
chatbot_status = {"status": "off"}
import sys
@app.route('/off', methods=['POST'])
def turn_off():
    global chatbot_status
    chatbot_status["status"] = "off"
    return jsonify(success=True)

@app.route('/status', methods=['GET'])  # This is the status of the chatbot
def get_chatbot_status():
    return jsonify(chatbot_status)


import subprocess

@app.route('/on', methods=['POST'])
def turn_on():
    global chatbot_status
    chatbot_status["status"] = "on"
    
    # Start the Flask application using subprocess
    subprocess.Popen(['python', 'app.py'])
    
    return jsonify(success=True)


if __name__ == "__main__":
    http_server = WSGIServer(('0.0.0.0', 5000), app)
    http_server.serve_forever()
