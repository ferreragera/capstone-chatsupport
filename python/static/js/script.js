class Chatbox {
    constructor() {
        this.args = {
            openButton: document.querySelector('.chatbox__button'),
            chatBox: document.querySelector('.chatbox__support'),
            sendButton: document.querySelector('.send__button')
        };

        this.state = false;
        this.messages = [];
        this.predictEndpoint = 'http://192.168.77.148/predict';
    }

    display() {
        const { openButton, chatBox, sendButton } = this.args;

        openButton.addEventListener('click', () => this.toggleState(chatBox));
        sendButton.addEventListener('click', () => this.onSendButton(chatBox));
        const node = chatBox.querySelector('input');
        node.addEventListener('keyup', ({ key }) => {
            if (key === 'Enter') {
                this.onSendButton(chatBox);
            }
        });
        this.addGreetingMessage(chatBox);
    }

    toggleState(chatbox) {
        this.state = !this.state;

        // show or hide the box
        if (this.state) {
            chatbox.classList.add('chatbox--active');
        } else {
            chatbox.classList.remove('chatbox--active');
        }
    }

     onSendButton(chatbox) {
        var textField = chatbox.querySelector('input');
        let text1 = textField.value;
        if (text1 === '') {
            return;
        }

        // Disable input field
        textField.disabled = true;

        // Simulate delay before sending response
        setTimeout(() => {
            // Add user's message to the chat history
            let msg1 = { name: 'User', message: text1, timestamp: new Date() };
            this.messages.push(msg1);

            // Send the user's message to the server
            fetch(this.predictEndpoint, {
                method: 'POST',
                body: JSON.stringify({ message: text1 }),
                mode: 'cors',
                headers: {
                    'Content-Type': 'application/json'
                },
            })
            .then(r => r.json())
            .then(r => {
                // Process the server's response
                let msg2 = { name: 'CVSU Admission System', message: r.answer, timestamp: new Date(), fullyDisplayed: false };
                this.messages.push(msg2);

                // Update the chat text and clear the input field
                this.updateChatText(chatbox);
                textField.value = '';
                textField.disabled = false; // Re-enable input field
            })
            .catch((error) => {
                // Handle errors from the server
                console.error('Error:', error);

                // Update the chat text and clear the input field
                this.updateChatText(chatbox);
                textField.value = '';
                textField.disabled = false; // Re-enable input field
            });
        }, 500); // Delay of 2 seconds (2000 milliseconds)
    }
    

    updateChatText(chatbox) {
        const container = chatbox.querySelector('.chatbox__messages');
        container.innerHTML = ''; // Clear container
    
        // Iterate over messages in reverse order
        for (let i = this.messages.length - 1; i >= 0; i--) {
            const msg = this.messages[i];
            const messageElement = document.createElement('div');
            messageElement.classList.add('messages__item');
    
            // Add timestamp message indicator
            const timestamp = document.createElement('div');
            timestamp.classList.add('message__timestamp');
            timestamp.textContent = new Date(msg.timestamp).toLocaleTimeString(); // Assuming msg.timestamp is a timestamp
            messageElement.appendChild(timestamp);
    
            if (msg.name === 'CVSU Admission System' && !msg.fullyDisplayed) {
                // Start streaming effect if message is not already fully displayed
                this.startStreamingEffect(container, msg);
            } else {
                if (msg.name === 'CVSU Admission System') {
                    const avatar = document.createElement('img');
                    avatar.classList.add('message__avatar');
                    avatar.src = `static/images/avatar1.png`;  // Assuming msg.avatar contains the filename of the avatar image
                    messageElement.appendChild(avatar);
    
                    const streamingText = document.createElement('span');
                    streamingText.classList.add('streaming-text');
                    streamingText.textContent = msg.message;
    
                    messageElement.classList.add('messages__item--visitor');
                    messageElement.appendChild(streamingText);
                } else {
                    messageElement.classList.add('messages__item--operator');
                    messageElement.textContent = msg.message;
                }
                container.appendChild(messageElement);
            }
        }
    
        container.scrollTop = container.scrollHeight;
    }
    
    
    
    
    
    startStreamingEffect(container, message) {
        const streamingText = document.createElement('span');
        streamingText.classList.add('streaming-text');
        streamingText.textContent = message.message;
    
        const cursor = document.createElement('span');
        cursor.textContent = '|';
        cursor.classList.add('cursor');
        cursor.style.visibility = 'hidden';
    
        // Add loading animation
        const loadingDiv = document.createElement('div');
        loadingDiv.classList.add('my', 'message');
        loadingDiv.innerHTML = `
        <div class="gray_jumping-dots bg-light">
            <span class="jumping-dots">
                <span class="dot-1"></span>
                <span class="dot-2"></span>
                <span class="dot-3"></span>
            </span>
        </div>`;
    
        // Hide streaming text initially
        streamingText.style.visibility = 'hidden';
    
        // Add elements to container
        container.insertBefore(loadingDiv, container.firstChild); // Insert before the first child
    
        // Start jumping dots animation
        setTimeout(() => {
            loadingDiv.style.opacity = '0'; // Hide the loading dots
            streamingText.style.visibility = 'visible'; // Show the streaming text
            // Start streaming effect after hiding the loading dots
            setTimeout(() => {
                container.removeChild(loadingDiv); // Remove the loading animation
                const messageElement = document.createElement('div');
                messageElement.classList.add('messages__item', 'messages__item--visitor');
                messageElement.appendChild(streamingText);
                container.insertBefore(messageElement, container.firstChild); // Insert before the first child
                this.startStreamingEffectInternal(streamingText, cursor, message);
            }, 1000); // Adjust the timing according to your preference
        }, 2000); // Adjust the timing according to your preference
    }
    
    startStreamingEffectInternal(streamingText, cursor, message) {
        let index = 0;
        const speed = 50; // Typing speed in milliseconds
        const text = message.message;
    
        function typeWriter() {
            if (index < text.length) {
                cursor.style.visibility = 'visible';
                streamingText.textContent = text.slice(0, index + 1);
                index++;
                setTimeout(typeWriter, speed);
            } else {
                cursor.style.visibility = 'hidden';
                message.fullyDisplayed = true; // Mark message as fully displayed
            }
        }
    
        // Start streaming effect
        typeWriter();
    }
    


    addGreetingMessage(chatbox) {
        const greetingMessage = {
            name: 'CVSU Admission System',
            message: 'Welcome to CVSU Admission Support. \nHow may I assist you today?',
            fullyDisplayed: false, // Mark greeting message as not fully displayed
            
            timestamp: new Date() // Add a timestamp for the greeting message
        };
    
        // Insert the greeting message at the beginning of the messages array
        this.messages.unshift(greetingMessage);
        this.updateChatText(chatbox);
    }
    
    
    
}

const chatbox = new Chatbox();
chatbox.display();
