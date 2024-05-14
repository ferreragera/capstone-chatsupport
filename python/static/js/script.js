class Chatbox {
    constructor() {
        this.args = {
            openButton: document.querySelector('.chatbox__button'),
            chatBox: document.querySelector('.chatbox__support'),
            sendButton: document.querySelector('.send__button')
        };

        this.state = false;
        this.messages = [];
      
        // this.predictEndpoint = 'http://192.168.77.148/predict';
    }

    display() {
        const { openButton, chatBox, sendButton } = this.args;

        openButton.addEventListener('click', () => this.toggleState(chatBox));
        sendButton.addEventListener('click', () => this.onSendButton(chatBox));
        const node = chatBox.querySelector('input');
        node.addEventListener('keyup', ({ key }) => {
            if (key === 'Enter') {
                // this.onSendButton(chatBox);
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

    // onSendButton(chatbox) {
    //     var textField = chatbox.querySelector('input');
    //     let text1 = textField.value;
    //     if (text1 === '') {
    //         return;
    //     }

    //     // Disable input field
    //     textField.disabled = true;

    //     // Simulate delay before sending response
    //     setTimeout(() => {
    //         // Add user's message to the chat history
    //         let msg1 = { name: 'User', message: text1, timestamp: new Date() };
    //         this.messages.push(msg1);

    //         // Send the user's message to the server
    //         fetch(this.predictEndpoint, {
    //             method: 'POST',
    //             body: JSON.stringify({ message: text1 }),
    //             mode: 'cors',
    //             headers: {
    //                 'Content-Type': 'application/json'
    //             },
    //         })
    //         .then(r => r.json())
    //         .then(r => {
    //             let msg2 = { name: 'CVSU Admission System', message: r.answer, timestamp: new Date(), fullyDisplayed: false };
    //             this.messages.push(msg2);

    //             this.updateChatText(chatbox);
    //             textField.value = '';
    //             textField.disabled = false; 
    //         })
    //         .catch((error) => {
    //             console.error('Error:', error);

    //             this.updateChatText(chatbox);
    //             textField.value = '';
    //             textField.disabled = false; 
    //         });
    //     }, 500); 
    // }

    
    updateChatText(chatbox) {
        const container = chatbox.querySelector('.chatbox__messages');
        container.innerHTML = ''; 
    
        // Iterate over messages in reverse order
        for (let i = this.messages.length - 1; i >= 0; i--) {
            const msg = this.messages[i];
            const messageElement = document.createElement('div');
            messageElement.classList.add('messages__item');
    
            if (msg.name === 'CVSU Admission System' && !msg.fullyDisplayed) {
                const existingStreamingText = container.querySelector('.streaming-text');
                if (existingStreamingText) {
                    existingStreamingText.innerHTML = msg.message;
                } else {
                    this.startStreamingEffect(container, msg);
                }
            } else {
                if (msg.name === 'CVSU Admission System') {
                    const visitorElement = document.createElement('div');
                    visitorElement.classList.add('messages__item--visitor');
    
                    const avatar = document.createElement('img');
                    avatar.classList.add('message__avatar');
                    avatar.src = `static/images/avatar1.png`;  
                    visitorElement.appendChild(avatar);
    
                    const messageContent = document.createElement('div');
                    messageContent.classList.add('message__content');
    
                    messageContent.innerHTML = msg.message;
    
                    visitorElement.appendChild(messageContent);
                    messageElement.appendChild(visitorElement);
                } else {
                    messageElement.classList.add('messages__item--operator');
                    messageElement.innerHTML = msg.message; 
                }
                
                
                container.appendChild(messageElement);
            }
    
            // Add timestamp message indicator
            const timestamp = document.createElement('div');
            timestamp.classList.add('message__timestamp');
            timestamp.textContent = new Date(msg.timestamp).toLocaleTimeString(); 
            messageElement.appendChild(timestamp);
        }
    
        container.scrollTop = container.scrollHeight;
    }
    

    startStreamingEffect(container, message) {

        if (message.message.includes('<')) {
            // If message content contains HTML tags, assume it's already rendered
            const messageElement = document.createElement('div');
            messageElement.classList.add('messages__item');
            messageElement.innerHTML = message.message;
            container.insertBefore(messageElement, container.firstChild); // Insert before the first child
            message.fullyDisplayed = true; // Mark message as fully displayed
        } else {
            const streamingText = document.createElement('span');
            //streamingText.classList.add('streaming-text'); Remove this line
            streamingText.innerHTML = message.message; // Use innerHTML to render HTML content
            
            const cursor = document.createElement('span');
            cursor.textContent = '|';
            cursor.classList.add('cursor');
            cursor.style.visibility = 'hidden';
            
            // Add loading animation
            const loadingDiv = document.createElement('div');
            loadingDiv.classList.add('my', 'message');
            loadingDiv.innerHTML = `
                <span class="jumping-dots">
                    <span class="dot-1"></span>
                    <span class="dot-2"></span>
                    <span class="dot-3"></span>
                </span>`;
            
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
                    messageElement.classList.add('messages__item');
                    messageElement.appendChild(streamingText);
                    container.insertBefore(messageElement, container.firstChild); // Insert before the first child
                    this.startStreamingEffectInternal(streamingText, cursor, message); // Pass streamingText to the internal function
                }, 500); // Adjust the timing according to your preference
            }, 1000); // Adjust the timing according to your preference
        }
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

            message: `Welcome to CVSU Admission Support. <br> How may I assist you today? <br>
            <style>
                .btn:hover {
                    background-color: #04AA6D;
                    color: white;
                  }
            </style>
            <div class= "d-flex flex-column" style="text-align: left;">
                <button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt('FAQs')">FAQs</button>
                <button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt('Visit Official Website')">Visit Official Website</button>
                <button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt('Contact Us')">Contact Us</button>
            </div>`,
            fullyDisplayed: true, 
            timestamp: new Date()
        };
    
        this.messages.unshift(greetingMessage);
        this.updatePrompt(chatbox);
    } 
    



    updatePrompt(chatbox) {
        const container = chatbox.querySelector('.chatbox__messages');
        container.innerHTML = ''; 

        for (let i = this.messages.length - 1; i >= 0; i--) {
            const msg = this.messages[i];
            if (msg.name === 'CVSU Admission System') {
                const messageElement = document.createElement('div');
                messageElement.classList.add('messages__item');
    

                if (msg.name === 'CVSU Admission System') {
                    const avatar = document.createElement('img');
                    avatar.classList.add('message__avatar');
                    avatar.src = `static/images/avatar1.png`; 
                    messageElement.appendChild(avatar);
                }
    
                // Render HTML content
                const contentElement = document.createElement('div');
                
                contentElement.innerHTML = msg.message;
                messageElement.appendChild(contentElement);
    
                // Add timestamp message indicator
                const timestamp = document.createElement('div');
                timestamp.classList.add('message__timestamp');
                timestamp.textContent = new Date(msg.timestamp).toLocaleTimeString(); 
                messageElement.appendChild(timestamp);
                // Add event listener if message is clickable

                if (msg.clickable) {
                    messageElement.classList.add('clickable');
                    messageElement.setAttribute('data-action', msg.action);
                    messageElement.addEventListener('click', () => this.handleConfirmation(msg.action, chatbox));
                }
    
                // Add the message element to the container
                container.appendChild(messageElement);
            }
        }
    
        // Scroll to the bottom of the container
        container.scrollTop = container.scrollHeight;
    }
    
    handlePrompt(prompt) {
        let response;
        switch (prompt) {
            case 'FAQs':
                response = 'Please select a category:';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Admission Process\')">Admission Process</button>';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Entrance Examination\')">Entrance Examination</button>';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Transferees\')">Transferees</button>';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Scholarship\')">Scholarship</button>';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Shifting\')">Shifting</button>';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Tuition Fee\')">Tuition Fee</button>';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Re-application\')">Re-application</button>';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Visit Official Website\')">Visit Official Website</button>';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Contact Us\')">Contact Us</button>';
                break;
                case 'Admission Process':
                response = 'Here are some frequently asked questions about the Admission Process:';
                response += '<div class="d-flex flex-column text-left">';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'FAQs\')">Back to FAQs</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Admission Process\')">What are the required documents that need to be submitted?</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Admission Process\')">Are the documents needed original or photocopy?</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Admission Process\')">Is it possible to submit the requirements that are already overdue?</button>';
                response += '</div>';
                break;
            case 'Entrance Examination':
                response = 'Here are some frequently asked questions about the Entrance Examination:';
                response += '<div class="d-flex flex-column text-left">';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'FAQs\')">Back to FAQs</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Entrance Examination\')">Can we reschedule our selected date for the entrance exam?</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Entrance Examination\')">Do they still do interviews in the admission process?</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Entrance Examination\')">Do they accept re-apply students if they fail the entrance exam?</button>';
                response += '</div>';
                break;
            case 'Transferees':
                response = 'Here are some frequently asked questions about Transferees:';
                response += '<div class="d-flex flex-column text-left">';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'FAQs\')">Back to FAQs</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Transferees\')">Do they accept transferees in the second semester?</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Transferees\')">What is the process of transferring to CvSU?</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Transferees\')">What are the needed requirements when transferring to other schools?</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Transferees\')">How can I apply or transfer to satellite campuses if I pass in the main campus?</button>';
                response += '</div>';
                break;
            case 'Scholarship':
                response = 'Here are some frequently asked questions about Scholarship:';
                response = 'For all scholarship concerns, please contact osasmain.scholarship@cvsu.edu.ph';
                response += '<br>If you have specific questions, feel free to ask.';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'FAQs\')">Back to FAQs</button>';
                break;
            case 'Shifting':
                response = 'Here are some frequently asked questions about Shifting:';
                response += '<br>Please inquire directly with the respective College for shifting processes and requirements.';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'FAQs\')">Back to FAQs</button>';
                break;
            case 'Tuition Fee':
                response = 'Here are some frequently asked questions about Tuition Fee';
                response += '<br>If you have specific questions, feel free to ask.';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'FAQs\')">Back to FAQs</button>';
                break;
            case 'Re-application':
                response = 'Here are some frequently asked questions about Re Application:';
                response += '<div class="d-flex flex-column text-left">';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'FAQs\')">Back to FAQs</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Re-application\')">What are the requirements for re-application?</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Re-application\')">Is it possible to give my slot to another student who failed the entrance exam?</button>';
                response += '<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'Re-application\')">How many applicants will be accepted for admission?</button>';
                response += '<br>Applicants just have to read the announcement that will be posted in the future to be guided.';
                response += '</div>';
                break;
            case 'Visit Official Website':
                window.open('https://www.cvsu.edu.ph', '_blank');
                response = 'Opening the official website in a new tab...';
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'FAQs\')">Back to FAQs</button>';
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
                    `For other offices, please visit our directory <br><a href="https://cvsu.edu.ph/contact-us-2/" target=”_blank”>here</a>.`;
                response += '<br><button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handlePrompt(\'FAQs\')">Back to FAQs</button>';
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
       
        const clickableMessages = this.args.chatBox.querySelectorAll('.clickable');
        clickableMessages.forEach((message) => {
            message.removeEventListener('click', () => this.handleConfirmation(message.getAttribute('data-action'), this.args.chatBox));
        });
    }

    handlePlaceholder(placeholderText) {
        const message = {
            name: 'User',
            message: placeholderText,
            timestamp: new Date(),
            fullyDisplayed: false
        };
        this.messages.push(message);
        this.updatePrompt(this.args.chatBox);
    }

}

const chatbox = new Chatbox();
chatbox.display();
