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
                    streamingText.innerHTML = msg.message; // Render HTML content
                    messageElement.classList.add('messages__item--visitor');
                    messageElement.appendChild(streamingText);
                } else {
                    messageElement.classList.add('messages__item--operator');
                    messageElement.innerHTML = msg.message; // Render HTML content
                }
                container.appendChild(messageElement);
            }
        }
    
        container.scrollTop = container.scrollHeight;
    }

    startStreamingEffect(container, message) {
        const streamingText = document.createElement('span');
        streamingText.classList.add('streaming-text');
        streamingText.innerHTML = message.message; // Use innerHTML to render HTML content
    
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
            }, 500); // Adjust the timing according to your preference
        }, 1000); // Adjust the timing according to your preference
    }

    startStreamingEffectInternal(streamingText, cursor, message) {
        let index = 0;
        const speed = 30; // Typing speed in milliseconds
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
            message: 'Welcome to CVSU Admission Support. \nHow may I assist you today? <br>\n<button onclick="chatbox.handlePrompt(\'FAQs\')">FAQs</button> <button onclick="chatbox.handlePrompt(\'Visit Official Website\')">Visit Official Website</button> <button onclick="chatbox.handlePrompt(\'Contact Us\')">Contact Us</button>',
            fullyDisplayed: false, // Mark greeting message as not fully displayed
            timestamp: new Date() // Add a timestamp for the greeting message
        };
    
        // Insert the greeting message at the beginning of the messages array
        this.messages.unshift(greetingMessage);
        this.updatePrompt(chatbox);
    }
    



    updatePrompt(chatbox) {
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
    
            if (msg.name === 'CVSU Admission System') {
                const avatar = document.createElement('img');
                avatar.classList.add('message__avatar');
                avatar.src = `static/images/avatar1.png`; // Assuming msg.avatar contains the filename of the avatar image
                messageElement.appendChild(avatar);
    
                const streamingText = document.createElement('span');
                streamingText.classList.add('streaming-text');
                streamingText.innerHTML = msg.message; // Render HTML content
                messageElement.classList.add('messages__item--visitor');
                messageElement.appendChild(streamingText);
            } else {
                messageElement.classList.add('messages__item--operator');
                if (msg.clickable) {
                    messageElement.classList.add('clickable');
                    messageElement.setAttribute('data-action', msg.action);
                }
                messageElement.innerHTML = msg.message; // Render HTML content
            }
    
            container.appendChild(messageElement);
        }
    
        container.scrollTop = container.scrollHeight;
    
        // Add event listeners to clickable elements
        const clickableMessages = chatbox.querySelectorAll('.clickable');
        clickableMessages.forEach((message) => {
            message.addEventListener('click', () => this.handleConfirmation(message.getAttribute('data-action'), chatbox));
        });
    }
    





    handlePrompt(prompt) {
        let response;
        switch (prompt) {
            case 'FAQs':
                response = 'Please select a category:';
                response += '<br><button class="btn btn-success rounded-pill" onclick="chatbox.handlePrompt(\'Admission Process\')">Admission Process</button>';
                response += '<br><button class="btn btn-success rounded-pill"onclick="chatbox.handlePrompt(\'Entrance Examination\')">Entrance Examination</button>';
                response += '<br><button class="btn btn-success rounded-pill-sm"onclick="chatbox.handlePrompt(\'Transferees\')">Transferees</button>';
                response += '<br><button class="btn btn-success rounded-pill-sm"onclick="chatbox.handlePrompt(\'Scholarship\')">Scholarship</button>';
                response += '<br><button class="btn btn-success rounded-pill btn-sm"onclick="chatbox.handlePrompt(\'Shifting\')">Shifting</button>';
                response += '<br><button class="btn btn-success rounded-pill"onclick="chatbox.handlePrompt(\'Tuition Fee\')">Tuition Fee</button>';
                response += '<br><button class="btn btn-success rounded-pill-sm"onclick="chatbox.handlePrompt(\'Re-application\')">Re-application</button>';
                response += '<br><button class="btn btn-success rounded-pill"onclick="chatbox.handlePrompt(\'Visit Official Website\')">Visit Official Website</button>';
                response += '<br><button class="btn btn-success rounded-pill"onclick="chatbox.handlePrompt(\'Contact Us\')">Contact Us</button>';
                break;
            case 'Admission Process':
                response = 'Here are some frequently asked questions about the Admission Process:';
                response += '<br>1. What are the required documents that need to be submitted?';
                response += '<br>2. Are the documents needed original or photocopy?';
                response += '<br>3. Is it possible to submit the requirements that are already overdue?';
                break;
            case 'Entrance Examination':
                response = 'Here are some frequently asked questions about the Entrance Examination:';
                response += '<br>1. Can we reschedule our selected date for the entrance exam?';
                response += '<br>2. Do they still do interviews in the admission process?';
                response += '<br>3. Do they accept re-apply students if they fail the entrance exam?';
                break;
            // Add cases for other categories
            case 'Visit Official Website':
                window.open('https://www.cvsu.edu.ph', '_blank');
                response = 'Opening the official website in a new tab...';
                break;
            case 'Contact Us':
                response = `Here are the contact details for various offices:<br><br>` +
                    `1. Office of the Student Affairs and Services - cvsumainosas@cvsu.edu.ph<br>` +
                    `   > For inquiries about course offering and admission inquiries<br><br>` +
                    `2. Office of the University Registrar - registrarmain@cvsu.edu.ph<br>` +
                    `   > For inquiries about TOR, Diploma, and Registrar related concerns<br><br>` +
                    `3. University Library - cvsulibrary@cvsu.edu.ph<br>` +
                    `   > For inquiries about library matters<br><br>` +
                    `4. University Webmaster - webmaster@cvsu.edu.ph<br>` +
                    `   > For technical problems found on the site and/or any feedback about the systems<br>` +
                    `For other offices, please visit our directory <br><a href="https://cvsu.edu.ph/contact-us-2/">here</a>.`;
                break;
            default:
                response = 'I apologize, but I cannot provide information for that request.';
        }
        const message = {
            name: 'CVSU Admission System',
            message: response,
            timestamp: new Date(),
            fullyDisplayed: false
        };
        this.messages.push(message);
        this.updatePrompt(this.args.chatBox);
    }

}

const chatbox = new Chatbox();
chatbox.display();
