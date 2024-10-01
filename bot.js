document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("userInput").addEventListener("keyup", function (event) {
        if (event.key === "Enter") {
            sendMessage();
        }
    });

    // Initial greeting from the chatbot
    setTimeout(function () {
        var initialGreeting = "Hi there! I'm a chatbot designed to assist with logistics-related queries. How can I help you?";
        appendMessage("bot", initialGreeting);
    }, 500);
});

function sendMessage() {
    var userInput = document.getElementById("userInput").value;
    if (userInput.trim() !== "") {
        appendMessage("user", userInput);

        // Simple question-answer pairs (replace this with your actual logic)
        var botResponse = getBotResponse(userInput);
        appendMessage("bot", botResponse);

        document.getElementById("userInput").value = ""; // Clear the input field
    }
}

function getBotResponse(userInput) {
    // Simple question-answer pairs (replace with your actual logic)
    var responses = {
        "how can i track my shipment":"To track your shipment, please provide your tracking number.",
        "ok thanks": "Thanks! If you have more questions, feel free to ask.",
        "can i change the delivery address for my order":"Sure, to update the delivery address, please provide your order number and the new address in the update section.",
        "how can i return my product":"To initiate a return, please provide your order number and the reason for the return. We'll guide you through the process.",
        "what is the customer care number":"You can reach our customer support at 1900 2104 0612 during our business hours. If you have an urgent matter, feel free to ask, and I'll do my best to assist you here. Thank you!!",
        "can i schedule a specific delivery time":"Currently, we do not offer scheduled delivery times. However, you can track your shipment for real-time updates on its delivery status.",
        "What are the shipping methods you offer":"We offer standard and express shipping options. Standard delivery takes 5-7 working days whereas express delivery takes 2-4 days. The choice is yours..",
        "What are the shipping rates for international deliveries":"Shipping rates vary based on the destination and package weight.",
        "thank you for your service":"Yeah Welcome ! It's our pleasure"// Add more question-answer pairs as needed
    };

    // Check if the user's input matches any predefined questions
    for (var question in responses) {
        if (userInput.toLowerCase().includes(question)) {
            return responses[question];
        }
    }

    // If no predefined answer is found, provide a generic response
    return "I'm sorry, I didn't understand that. Can you please ask another question?";
}

function appendMessage(sender, message) {
    var chatBox = document.getElementById("chatBox");
    var messageDiv = document.createElement("div");
    messageDiv.className = sender === "user" ? "user-message" : "bot-message";
    messageDiv.textContent = message;
    chatBox.appendChild(messageDiv);
}
