from gevent.pywsgi import WSGIServer
from flask import Flask, render_template, request, jsonify
from flask_cors import CORS
from markupsafe import escape
import subprocess
import mysql.connector
import json
from chat import get_response
from better_profanity import profanity

conn = mysql.connector.connect(
    host='127.0.0.1',
    user='root',
    password='',
    database='capstone-chatsupport'
)

cursor = conn.cursor()
app = Flask(__name__)

CORS(app, resources={r"/train": {"origins": "*"}}) 

with open('intents.json', 'r') as intents_file:
    intents = json.load(intents_file)

@app.get("/")
def index_get():
    return render_template("index.php")


# @app.post("/predict")
# def predict():
#     text = escape(request.get_json().get("message"))
#     matching_intent = find_matching_intent(text)

#     if matching_intent:
#         responses = matching_intent["responses"]
#         response = "\n".join(responses)
#     else:
#         response = "I don't have specific information for that topic."
 
#     censored_response = profanity.censor(response)
#     message = {"answer": censored_response}
#     return jsonify(message)

# def find_matching_intent(text):
#     for intent in intents["intents"]:
#         if text.lower() in [pattern.lower() for pattern in intent["patterns"]]:
#             return intent
#     return None

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
    

    



if __name__ == "__main__":
    http_server = WSGIServer(('0.0.0.0', 5000), app)
    http_server.serve_forever()