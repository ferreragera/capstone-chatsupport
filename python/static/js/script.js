class Chatbox {
    constructor(intents) {
        
        this.args = {
            openButton: document.querySelector('.chatbox__button'),
            chatBox: document.querySelector('.chatbox__support'),
            sendButton: document.querySelector('.send__button')
        };

        this.intents = intents;
        this.state = false;
        this.messages = [];

        this.predictEndpoint = 'http://192.168.1.25:5000/predict';

        this.setupEventListeners();
    }

    setupEventListeners() {
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

        if (this.state) {
            chatbox.classList.add('chatbox--active');
        } else {
            chatbox.classList.remove('chatbox--active');
        }
    }

    updateChatText(chatbox) {
        const container = chatbox.querySelector('.chatbox__messages');
        container.innerHTML = '';

        // Iterate over messages in reverse order
        for (let i = this.messages.length - 1; i >= 0; i--) {
            const msg = this.messages[i];
            const messageElement = document.createElement('div');
            messageElement.classList.add('messages__item');

            if (msg.name === 'User') {
                messageElement.classList.add('messages__item--visitor');
                messageElement.innerHTML = msg.message;
            } else if (msg.name === 'CVSU Admission System' && !msg.fullyDisplayed) {
                const existingStreamingText = container.querySelector('.streaming-text');
                if (existingStreamingText) {
                    existingStreamingText.innerHTML = msg.message;
                } else {
                    this.startStreamingEffect(container, msg);
                }
            } else {
                messageElement.classList.add('messages__item--operator');
                messageElement.innerHTML = msg.message;
                container.insertBefore(messageElement, container.firstChild);
            }

            // Add timestamp message indicator
            const timestamp = document.createElement('div');
            timestamp.classList.add('message__timestamp');
            timestamp.textContent = new Date(msg.timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            messageElement.appendChild(timestamp);

            // Apply word-break property to message content
            const messageContent = messageElement.querySelector('.msg_content');
            if (messageContent) {
                messageContent.style.wordBreak = 'normal';
            }
        }

        container.scrollTop = container.scrollHeight;
    }

    startStreamingEffect(container, message) {
        if (message.message.includes('<')) {
            const messageElement = document.createElement('div');
            messageElement.classList.add('messages__item');
            messageElement.innerHTML = message.message;
            container.insertBefore(messageElement, container.firstChild);
            message.fullyDisplayed = true;
        } else {
            const streamingText = document.createElement('span');
            streamingText.innerHTML = message.message;

            const cursor = document.createElement('span');
            cursor.textContent = '|';
            cursor.classList.add('cursor');
            cursor.style.visibility = 'hidden';

            const loadingDiv = document.createElement('div');
            loadingDiv.classList.add('my', 'message');
            loadingDiv.innerHTML = `
                <span class="jumping-dots">
                    <span class="dot-1"></span>
                    <span class="dot-2"></span>
                    <span class="dot-3"></span>
                </span>`;

            streamingText.style.visibility = 'hidden';
            container.insertBefore(loadingDiv, container.firstChild);

            setTimeout(() => {
                loadingDiv.style.opacity = '0';
                streamingText.style.visibility = 'visible';
                setTimeout(() => {
                    container.removeChild(loadingDiv);
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('messages__item');
                    messageElement.appendChild(streamingText);
                    container.insertBefore(messageElement, container.firstChild);
                    this.startStreamingEffectInternal(streamingText, cursor, message);
                }, 500);
            }, 1000);
        }
    }

    addGreetingMessage(chatbox) {
        const greetingMessage = {
            name: 'CVSU Admission System',
            message: `Welcome to CVSU Admission Support. How may I assist you today? <br>
            <style>
                .btn {
                    word-wrap: normal; /* Disable normal word wrapping */
                    overflow-wrap: break-word; /* Allow breaking long words */
                    white-space: normal;
                    font-size: 16px;
                }
                .btn:hover {
                    background-color: #04AA6D;
                    color: white;
                }
            </style>
            <div class="d-flex flex-column" style="text-align: left;">
                <button class="btn btn-sm border-success mt-2" style="border-radius: 20px; word-break: break-all;" onclick="chatbox.handlePrompt('FAQs')">FAQs</button>
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

                const avatar = document.createElement('img');
                avatar.classList.add('message__avatar');
                avatar.src = `static/images/chat2.png`;
                messageElement.appendChild(avatar);

                const contentElement = document.createElement('div');
                contentElement.innerHTML = msg.message;
                messageElement.appendChild(contentElement);

                const timestamp = document.createElement('div');
                timestamp.classList.add('message__timestamp');
                timestamp.textContent = new Date(msg.timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                messageElement.appendChild(timestamp);

                container.appendChild(messageElement);
            }
        }

        container.scrollTop = container.scrollHeight;
    }

    handlePrompt(prompt) {
        const dynamicResponses = {
            'FAQs': {
                message: 'Please select a category:',
                options: ['Admission Process', 'Entrance Examination', 'Transferees', 'Tuition Fee', 'Re-application', 'Visit Official Website', 'Contact Us']
            },
            'Admission Process': {
                message: 'Here are some frequently asked questions about the Admission Process:',
                options: ['What are the required documents that need to be submitted?', 'Are the documents needed original or photocopy?', 'Is it possible to submit the requirements that are already overdue?','Is it possible for the students who cannot submit the requirements personally?' ,'Can your admission papers be submitted by someone else?', 'Is it possible for the submitted original documents to be returned?','Is it possible to change the wrong data input in forms?','When is the deadline for submitting the requirements?','Back']
            },
           
            'Entrance Examination': {
                message: 'Here are some frequently asked questions about the Entrance Examination:',
                options: ['Can we reschedule our selected date for the entrance exam?', 'Do they still do interviews in the admission process?', 'Do they accept re-apply students if they fail the entrance exam?','Where will the entrance exam be held?','What kind of questions will appear in the entrance exam?', 'Back']
            },
            'Transferees': {
                message: 'Here are some frequently asked questions about Transferees:',
                options: ['Do they accept transferees in the second semester?', 'What are the needed requirements when transferring to other schools?', 'How can I apply or transfer to satellite campuses if I pass in the main campus?', 'Back']
            },
            'Tuition Fee': {
                message: 'Here are some frequently asked questions about Tuition Fee:<br>If you have specific questions, feel free to ask.',
                options: ['Does the university have a tuition fee?','Is everyone qualified for Free Tuition Law?','Back']
            },
            'Re-application': {
                message: 'Here are some frequently asked questions about Re-application:',
                options: ['What are the requirements for re-application?', 'Is it possible to give my slot to another student who failed the entrance exam?', 'How many applicants will be accepted for admission?', 'Back']
            },
            'Visit Official Website': {
                
                message: 'Opening the official website in a new tab...',
                
                options: ['Back']
            },
            'Contact Us': {
                message: 'Here are the contact details for various offices:<br><br>' +
                    '1. Office of the Student Affairs and Services - cvsumainosas@cvsu.edu.ph<br>' +
                    '   > For inquiries about course offering and admission inquiries<br><br>' +
                    '2. Office of the University Registrar - registrarmain@cvsu.edu.ph<br>' +
                    '   > For inquiries about TOR, Diploma, and Registrar related concerns<br><br>' +
                    '3. University Library - cvsulibrary@cvsu.edu.ph<br>' +
                    '   > For inquiries about library matters<br><br>' +
                    '4. University Webmaster - webmaster@cvsu.edu.ph<br>' +
                    '   > For technical problems found on the site and/or any feedback about the systems<br>' +
                    'For other offices, please visit our directory <br><a href="https://cvsu.edu.ph/contact-us-2/" target="_blank">here</a>.',
                options: ['Back']
                
            },
            // Admission Process
            'What are the required documents that need to be submitted?': {
                message: `The following are the required documents/credentials:<br><br>
                    <strong>FIRST YEAR APPLICANTS</strong><br>
                    <u>For Grade 12 Students</u><br>
                    - Copy of accomplished application form for admission<br>
                    - Photocopy of Grade 11 card<br>
                    - Certificate from the Principal or Adviser indicating that the applicant is currently enrolled as Grade 12 student and the strand is also indicated<br>
                    <em>The certificate must be originally signed. E-signature is not allowed.</em><br>
                    - Ordinary A4-size folder<br><br>
                    <u>For SHS Graduate</u><br>
                    - Copy of accomplished application form for admission<br>
                    - Photocopy of completed Grade 12 report card<br>
                    - Certificate of non-issuance of Form 137 for College Admission (this certification shall prove that the applicant has never been enrolled in another university/college)<br>
                    - Ordinary A4-size folder<br><br>
                    <u>For ALS Passers</u><br>
                    - Copy of accomplished application form for admission<br>
                    - Photocopy of Certificate of Rating (COR) with eligibility to enroll in college<br>
                    - Ordinary A4-size folder<br><br>
                    <u>For Associate, Certificate, Vocational, or Diploma Degree Holder</u><br>
                    - Copy of accomplished application form for admission<br>
                    - Photocopy of Transcript of Records (TOR) with graduation date<br>
                    - Ordinary A4-size folder<br><br>
                    <strong>TRANSFEREE APPLICANTS</strong><br>
                    - Copy of accomplished application form for admission<br>
                    - Photocopy of Transcript of Records/Certificate of Grades<br><br>
                    <strong>SECOND-COURSE APPLICANTS</strong><br>
                    - Copy of accomplished application form for admission<br>
                    - Photocopy of Transcript of Records with graduation date<br>
                    - Ordinary Short white folder<br><br>
                    <strong>TCP APPLICANTS</strong><br>
                    - Copy of accomplished application form for admission<br>
                    - Photocopy of Transcript of Records with indication of graduation date`,
                options: ['Back']
            },
            'Are the documents needed original or photocopy?': {
                message: ' Depending on the category of the applicant, there are requirement document that need to be original copy and the other just a photocopy',
                options: ['Back']
            },
            'Is it possible to submit the requirements that are already overdue?': {
                message: " Hello! Currently, there are no official announcements regarding an extension for submitting requirements to CVSU. If you have exceptional circumstances, please contact the CVSU Admissions Office directly for assistance. Thank you!",
                options: ['Back']
            },
            'When is the deadline for submitting the requirements?': {
                message: ' Hello! The deadline for submitting the requirements is posted in the official announcement. Please check the CVSU website for more details: <br><a href="https://cvsu.edu.ph/category/announcements/" target="_blank">Official Announcement</a>. Thank you!',
                options: ['Back']
            },
            'Is it possible for the students who cannot submit the requirements personally?': {
                message: ' Yes, if the applicant cannot submit the application personally, he/she can have his/her parents to submit his/her application. If it will be submitted by a friend or a classmate, the applicant must provide an authorization letter.',
                options: ['Back']
            },
            'Can your admission papers be submitted by someone else?': {
                message: '  Yes, if the applicant cannot submit the application personally, he/she can have his/her parents to submit his/her application. If it will be submitted by a friend or a classmate, the applicant must provide an authorization letter.',
                options: ['Back']
            },
            'Is it possible to change the wrong data input in forms?': {
                message: '  Once there is a control number in the application form, information details are locked and cannot be edited.',
                options: ['Back']
            },
            'Is it possible for the submitted original documents to be returned?': {
                message: ' All requirements submitted at Admission Process cannot be returned to the applicant.',
                options: ['Back']
            },

            //Entrance Examination
            'Can we reschedule our selected date for the entrance exam?': {
                message: 'Hello! Yes, it is possible to reschedule your selected date for the entrance exam, but it depends on the reason. Please contact the CVSU Admissions Office with the details of your situation, and they will assist you accordingly. Thank you!',
                options: ['Back']
            },
            'Do they still do interviews in the admission process?': {
                message: 'Hello! Yes, interviews are still part of the admission process, but only for specific programs. These include BS Nursing, BS Midwifery, BS Medical Technology, BS Hospitality Management, and BS Tourism Management. Thank you!',
                options: ['Back']
            },

            'Do they accept re-apply students if they fail the entrance exam?': {
                message: 'Please refer to the <br><a href="https://cvsu.edu.ph/category/announcements/" target="_blank">CvSU Website</a> for the exact date and further details. Thank you!',
                options: ['Back']
            },
            'Where will the entrance exam be held?': {
                message: ' The entrance exam will take place at the International Convention Center (ICON) located at CVSU Main Campus.',
                options: ['Back']
            },
            'What kind of questions will appear in the entrance exam?': {
                message: "I'm sorry, but I can't provide specific details about the content of entrance exams. It's essential to maintain the confidentiality and integrity of these assessments to ensure fairness for all candidates. If you need assistance with preparing for exams, I'm here to offer guidance and support on general study strategies and concepts.",
                options: ['Back']
            },
            //Transferee
            'Do they accept transferees in the second semester?': {
                message: "Transferee admissions in the second semester vary depending on program availability and specific course requirements. It's recommended to check with the respective department or program coordinator for information on whether they accept transferees during this period.",
                options: ['Back']
            },
            'What are the needed requirements when transferring to other schools?': {
                message: "Requirements for transferring to other schools can vary depending on the institution and program. To ensure a smooth transfer process, it's advisable to review the specific requirements outlined in the announcement posted on the official website of the school you intend to transfer to.",
                options: ['Back']
            },
            'How can I apply or transfer to satellite campuses if I pass in the main campus?': {
                message: "If you've been admitted to the main campus of Cavite State University (CvSU) and wish to transfer to one of its satellite campuses, refer to the announcement provided by the respective satellite campus for guidance on the application process. Each satellite campus may have its own set of procedures and requirements for transfer applicants.",
                options: ['Back']
            },

            //Tuition 
            'Does the university have a tuition fee?': {
                message: 'Yes, Cavite State University (CvSU) typically charges tuition fees for its academic programs. However, as mentioned in the letter, students enrolled in courses leading to a bachelor’s degree are exempted from paying tuition and other school fees due to the full implementation of Republic Act No. 10931, the "Universal Access to Quality Tertiary Education Act of 2017." All queries regarding scholarship concerns can be addressed thru registrarmain@cvsu.edu.ph',
                options: ['Back']
            },
            'Is everyone qualified for Free Tuition Law?': {
                message: "All (eligible) Filipino students enrolled in courses leading to a bachelor's degree in state universities and colleges (SUCs), local universities and colleges (LUCs) and technical-vocational schools will be exempted from paying tuition and other school fees",
                options: ['Back']
            },

            //Re-application
            'What are the requirements for re-application?': {
                message: 'For re-application, applicants are advised to await the announcement that will be posted in the future for guidance on the necessary requirements.',
                options: ['Back']
            },

            'Is it possible to give my slot to another student who failed the entrance exam?': {
                message: 'No, applicants do not have the authority to transfer their slot to another applicant.',
                options: ['Back']
            },

            'How many applicants will be accepted for admission?': {
                message: 'The number of applicants accepted for admission depends on the capacity of each college.',
                options: ['Back']
            },

        };

        if (prompt === 'Visit Official Website') {
            window.open('https://www.cvsu.edu.ph', '_blank');
        }

        const data = dynamicResponses[prompt];
        let response = data.message;
        if (data.options.length > 0) {
            response += '<div class="d-flex flex-column text-left">';
            data.options.forEach(option => {
                response += `<button class="btn btn-sm border-success mt-2" style="border-radius: 20px;  word-break: normal;" onclick="chatbox.handlePrompt('${option === 'Back' ? 'FAQs' : option}')">${option}</button>`;
            });
            response += '</div>';
        }
    
        const message = {
            name: 'CVSU Admission System',
            message: response,
            timestamp: new Date(),
            fullyDisplayed: false
        };
        this.messages.push(message); // Push the message to the end of the array
        this.updatePrompt(this.args.chatBox); // Update the chatbox display
    }

    

    handleUnknownPrompt() {
        const message = {
            name: 'CVSU Admission System',
            message: 'I apologize, but I cannot provide information for that request.',
            timestamp: new Date(),
            fullyDisplayed: false
        };
        this.messages.unshift(message);
        this.updatePrompt(this.args.chatBox);
    }

    onSendButton(chatbox) {
        const textField = chatbox.querySelector('input');
        const text = textField.value.trim();
        if (text === "") {
            return;
        }

        // Store the user input message in the messages array
        const userMessage = {
            name: 'User',
            message: text,
            timestamp: new Date(),
            fullyDisplayed: true
        };
        this.messages.unshift(userMessage); // Add the user message to the beginning of the array
    
        fetch('http://192.168.1.25:5000/predict', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: text })
        })
        .then(response => response.json())
        .then(data => {
            const response = data.response.join('<br>'); // Join responses with '<br>' for multiple lines
            this.displayBotMessage(text, response); // Pass both user input and bot response to displayBotMessage
        })
        .catch(error => {
            console.error('Error:', error);
        });
    
        textField.value = '';
    }
    displayBotMessage(userInput, botResponse) {
        const chatboxMessages = document.querySelector('.chatbox__messages');
    
        // Create and prepend user message
        const userMessageDiv = document.createElement('div');
        userMessageDiv.classList.add('messages__item', 'messages__item--operator');
        userMessageDiv.innerHTML = userInput;
        chatboxMessages.insertBefore(userMessageDiv, chatboxMessages.firstChild);
    
        // Create and prepend bot response
        const botMessageDiv = document.createElement('div');
        botMessageDiv.classList.add('messages__item', 'messages__item--visitor');
    
        const avatar = document.createElement('img');
        avatar.classList.add('message__avatar');
        avatar.src = `static/images/chat2.png`;
        botMessageDiv.appendChild(avatar);
    
        const contentElement = document.createElement('div');
        contentElement.innerHTML = botResponse;
        botMessageDiv.appendChild(contentElement);
    
        const timestamp = document.createElement('div');
        timestamp.classList.add('message__timestamp');
        timestamp.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        botMessageDiv.appendChild(timestamp);
    
        chatboxMessages.insertBefore(botMessageDiv, chatboxMessages.firstChild);
    
        // Add "Did that response answer your question?" prompt
        const feedbackDiv = document.createElement('div');
        feedbackDiv.classList.add('messages__item', 'messages__item--visitor');
        feedbackDiv.innerHTML = `
            Did that response answer your question?
            <button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handleFeedback(true)">Yes</button>
            <button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handleFeedback(false)">No</button>
        `;
        chatboxMessages.insertBefore(feedbackDiv, chatboxMessages.firstChild);
    
        
    }
    


    matchUserInputWithPatterns(userInput) {
        return new Promise((resolve, reject) => {
            fetch('/match_pattern', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ userInput: userInput })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to match pattern');
                }
                return response.json();
            })
            .then(data => {
                resolve(data.map(match => match.pattern)); // Resolve with an array of matched patterns
            })
            .catch(error => {
                console.error('Error matching pattern:', error);
                reject(error);
            });
        });
    }

    // handleFeedback(isHelpful) {
    //     const chatboxMessages = document.querySelector('.chatbox__messages');
    //     const feedbackResponseDiv = document.createElement('div');
    //     feedbackResponseDiv.classList.add('messages__item', 'messages__item--visitor');

    //     if (isHelpful) {
    //         feedbackResponseDiv.innerHTML = "I'm glad to help! :)";
    //         chatboxMessages.insertBefore(feedbackResponseDiv, chatboxMessages.firstChild);
    //         chatboxMessages.scrollTop = chatboxMessages.scrollHeight;
    //     } else {
    //         feedbackResponseDiv.innerHTML = "I apologize, but I didn't quite understand that. Could you please rephrase your question or choose from the suggestions below?";

    //         // Retrieve previous user input
    //         const previousUserInput = this.messages[0].message;
    //         console.log(previousUserInput);
    //         // Call matchUserInputWithPatterns asynchronously
    //         this.matchUserInputWithPatterns(previousUserInput)
    //             .then(matchedSuggestions => {
    //                 console.log(matchedSuggestions);
    //                 if (matchedSuggestions.length > 0) {
    //                     matchedSuggestions.forEach(suggestion => {
    //                         const suggestionButton = document.createElement('button');
    //                         suggestionButton.classList.add('btn', 'btn-sm', 'border-success', 'mt-2');
    //                         suggestionButton.style.borderRadius = '20px';
    //                         suggestionButton.style.wordBreak = 'normal';
    //                         suggestionButton.textContent = suggestion;
    //                         suggestionButton.addEventListener('click', () => {
    //                             this.displayPatternResponse(suggestion);
    //                             suggestionButton.disabled = true; // Disable the button after click
    //                         });
                            
    //                         feedbackResponseDiv.appendChild(suggestionButton);
    //                     });
                        
                      
    //                 } else {
    //                     feedbackResponseDiv.innerHTML += "<br>No suggestions available.";
    //                 }
                    
    //                 // Append the feedback response to the chatbox
    //                 chatboxMessages.insertBefore(feedbackResponseDiv, chatboxMessages.firstChild);
    //                 chatboxMessages.scrollTop = chatboxMessages.scrollHeight;

    //                 // Add additional prompt
    //                 const additionalPromptDiv = document.createElement('div');
    //                 additionalPromptDiv.classList.add('messages__item', 'messages__item--visitor');
    //                 additionalPromptDiv.innerHTML = `
    //                     If your question is unanswered properly, you can submit it in the feedback box below.
    //                 `;
    //                 chatboxMessages.insertBefore(additionalPromptDiv, chatboxMessages.firstChild);

    //                 chatboxMessages.scrollTop = chatboxMessages.scrollHeight;

    //                 // Loop until the user responds with "Yes"
                    
    //             })
    //             .catch(error => {
    //                 console.error('Error fetching suggestions:', error);
    //                 feedbackResponseDiv.innerHTML += "<br>Error fetching suggestions.";

    //                 // Append the feedback response to the chatbox even if there's an error
    //                 chatboxMessages.insertBefore(feedbackResponseDiv, chatboxMessages.firstChild);
    //                 chatboxMessages.scrollTop = chatboxMessages.scrollHeight;

    //                 // Loop until the user responds with "Yes"
                    
    //             });
    //     }
    // }

    handleFeedback(isHelpful) {
        const chatboxMessages = document.querySelector('.chatbox__messages');
        const feedbackResponseDiv = document.createElement('div');
        feedbackResponseDiv.classList.add('messages__item', 'messages__item--visitor');

        if (isHelpful) {
            feedbackResponseDiv.innerHTML = "I'm glad to help! :)";
            chatboxMessages.insertBefore(feedbackResponseDiv, chatboxMessages.firstChild);
            chatboxMessages.scrollTop = chatboxMessages.scrollHeight;
        } else {
            feedbackResponseDiv.innerHTML = "I apologize, but I didn't quite understand that. Could you please rephrase your question or choose from the suggestions below?";

            // Retrieve previous user input
            const previousUserInput = this.messages[0].message;
            console.log(previousUserInput);
            // Call matchUserInputWithPatterns asynchronously
            this.matchUserInputWithPatterns(previousUserInput)
                .then(matchedSuggestions => {
                    console.log(matchedSuggestions);
                    if (matchedSuggestions.length > 0) {
                        matchedSuggestions.forEach(suggestion => {
                            const suggestionButton = document.createElement('button');
                            suggestionButton.classList.add('btn', 'btn-sm', 'border-success', 'mt-2');
                            suggestionButton.style.borderRadius = '20px';
                            suggestionButton.style.wordBreak = 'normal';
                            suggestionButton.textContent = suggestion;
                            suggestionButton.addEventListener('click', () => {
                                this.displayPatternResponse(suggestion);
                                suggestionButton.disabled = true; // Disable the button after click
                            });
                            feedbackResponseDiv.appendChild(suggestionButton);
                        });

                        // Add "Something else?" button
                            const somethingElseButton = document.createElement('button');
                            somethingElseButton.classList.add('btn', 'btn-sm', 'border-success', 'mt-2');
                            somethingElseButton.style.borderRadius = '20px';
                            somethingElseButton.style.wordBreak = 'normal';
                            somethingElseButton.textContent = "Something else?";
                            somethingElseButton.addEventListener('click', () => {
                                // Open the feedback modal
                                $('#feedbackModal').modal('show');
                            });
                            feedbackResponseDiv.appendChild(somethingElseButton);

                    } else {
                        feedbackResponseDiv.innerHTML += "<br>No suggestions available.";
                    }

                    // Append the feedback response to the chatbox
                    chatboxMessages.insertBefore(feedbackResponseDiv, chatboxMessages.firstChild);
                    chatboxMessages.scrollTop = chatboxMessages.scrollHeight;

                    // Add additional prompt
                    const additionalPromptDiv = document.createElement('div');
                    additionalPromptDiv.classList.add('messages__item', 'messages__item--visitor');
                    additionalPromptDiv.innerHTML = `
                        If your question is unanswered properly, you can submit it in the feedback box below.
                    `;
                    chatboxMessages.insertBefore(additionalPromptDiv, chatboxMessages.firstChild);

                    chatboxMessages.scrollTop = chatboxMessages.scrollHeight;

                })
                .catch(error => {
                    console.error('Error fetching suggestions:', error);
                    feedbackResponseDiv.innerHTML += "<br>Error fetching suggestions.";

                    // Append the feedback response to the chatbox even if there's an error
                    chatboxMessages.insertBefore(feedbackResponseDiv, chatboxMessages.firstChild);
                    chatboxMessages.scrollTop = chatboxMessages.scrollHeight;
                });
        }
    }

    askFeedbackAgain() {
        const chatboxMessages = document.querySelector('.chatbox__messages');
        const feedbackPromptDiv = document.createElement('div');
        feedbackPromptDiv.classList.add('messages__item', 'messages__item--visitor');
        feedbackPromptDiv.innerHTML = `
            Did that response answer your question?
            <button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handleFeedback(true)">Yes, thank you.</button>
            <button class="btn btn-sm border-success mt-2" style="border-radius: 20px;" onclick="chatbox.handleFeedback(false)">No, I need more help.</button>
        `;
        
        chatboxMessages.insertBefore(feedbackPromptDiv, chatboxMessages.firstChild);
        chatboxMessages.scrollTop = chatboxMessages.scrollHeight;
    }
    
    displayPatternResponse(pattern) {
        fetch('http://192.168.1.25:5000/predict', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: pattern })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch pattern response.');
            }
            return response.json();
        })
        .then(data => {
            // Display the response in the chatbox
            const chatboxMessages = document.querySelector('.chatbox__messages');
    
            const botMessageDiv = document.createElement('div');
            botMessageDiv.classList.add('messages__item', 'messages__item--visitor');
            
            const avatar = document.createElement('img');
            avatar.classList.add('message__avatar');
            avatar.src = `static/images/chat2.png`;
            botMessageDiv.appendChild(avatar);
    
            const contentElement = document.createElement('div');
            contentElement.innerHTML = data.response.join('<br>');
            botMessageDiv.appendChild(contentElement);
    
            const timestamp = document.createElement('div');
            timestamp.classList.add('message__timestamp');
            timestamp.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            botMessageDiv.appendChild(timestamp);
    
            chatboxMessages.insertBefore(botMessageDiv, chatboxMessages.firstChild);
            chatboxMessages.scrollTop = chatboxMessages.scrollHeight;
            this.askFeedbackAgain();
        })
        .catch(error => {
            console.error('Error displaying pattern response:', error);
        });
    }
    

}


const chatbox = new Chatbox();
chatbox.display();

