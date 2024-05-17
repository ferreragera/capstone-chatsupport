from gevent.pywsgi import WSGIServer
from flask import Flask, render_template, request, jsonify
from flask_cors import CORS
from markupsafe import escape
import subprocess
import mysql.connector
import json
import random
from chat import get_response
from better_profanity import profanity
from difflib import SequenceMatcher

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



if __name__ == "__main__":
    http_server = WSGIServer(('0.0.0.0', 5000), app)
    http_server.serve_forever()