from flask import Flask, jsonify, request
from flask_cors import CORS
import subprocess
from flask import Flask, render_template, request, jsonify
import os
import signal
import psutil
import mysql.connector
from gevent.pywsgi import WSGIServer
from flask import Flask, render_template, request, jsonify
from flask_cors import CORS
from markupsafe import escape
import subprocess
from better_profanity import profanity
from difflib import SequenceMatcher
from flask import send_file
from flask import Flask, jsonify, request
from fuzzywuzzy import process
import datetime

conn = mysql.connector.connect(
    host='127.0.0.1',
    user='root',
    password='',
    database='capstone-chatsupport'
)

cursor = conn.cursor()
app = Flask(__name__)

CORS(app, resources={r"/on": {"origins": "*"}})

# Global variable to store the process ID
process_id = None

@app.get("/")
def index_get():
    return render_template("index.php")

@app.route('/on', methods=['POST'])
def turningOn():
    global process_id
    if process_id and psutil.pid_exists(process_id):
        return jsonify({"success": False, "message": "Application is already running."})

    process = subprocess.Popen(['python', 'app.py'], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    process_id = process.pid
    return jsonify({"success": True, "pid": process_id})

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
    app.run(host='0.0.0.0', port=5000)
