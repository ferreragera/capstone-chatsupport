from flask import Flask, request, jsonify
from flask_cors import CORS
import os
import subprocess

app = Flask(__name__)
CORS(app)  # Add this line to enable CORS for all routes

process = None  # Global variable to store the process

@app.route('/start_app', methods=['GET'])
def start_app():
    global process
    if process is None or process.poll() is not None:  # Check if process is not running
        process = subprocess.Popen(['python', 'app.py'])
        return jsonify(status='running'), 200
    else:
        return 'app.py is already running', 409

@app.route('/stop_app', methods=['GET'])
def stop_app():
    global process
    if process is not None and process.poll() is None:  # Check if process is running
        process.terminate()
        process.wait()  # Wait for the process to terminate
        process = None
        return jsonify(status='stopped'), 200
    else:
        return 'app.py is not running', 409

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5001)
