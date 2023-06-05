# How to create a ChatBot with WhatsApp and ChatGPT using PHP  

## Necessary Resources
1.	Meta Developer Account (https://developers.facebook.com/).
2.	CharGPT Account (https://platform.openai.com/apps).
3.	Hosting to have a valid domain (FQDN) with certificate.
4.	Install git (https://git-scm.com/downloads).
5.	Have a Github account, to keep control of source code (https://desktop.github.com/)
6.	Install Visual Studio Code (https://code.visualstudio.com/download)
7.	Add the following extensions in Visual Studio Code:<br>
    a.	Color Highlight <br>
    b.	Community Material Theme <br>
    c.	Material Icon Theme <br>
    d.	Material Them Icons<br>
8.	Install Postman to test the API (https://www.postman.com/downloads/).

## Create Project in Meta
Create a project in Meta Developer Account to obtain an API Key (Token) and our URL. If you want to see more detail on how to create it, we recommend you consult the following manual:<br>
[Chatbot_WhatsApp-ChatGPT-Api_With_PHP.pdf](https://github.com/VitalPBX/chatbot-whatsApp-ChatGPT-with-PHP/blob/main/Chatbot_WhatsApp-ChatGPT-Api_With_PHP.pdf)
    

## Create ChatGPT/OpenAI Token
Enter our OpenAI account and go to:<br>
https://platform.openai.com/apps

Then in the upper right side go to our account. Create de API Key. If you want to see more detail on how to create it, we recommend you consult the following manual:

## Project in PHP to make a Chatbot integrating WhatsApp and ChatGPT

After having the two Tokens we proceed to create our project using Visual Studio Code. We download the complete code in:<br>
https://github.com/VitalPBX/chatbot-whatsApp-ChatGPT-with-PHP


### Project Files
•	**chatbot_database.sql**, database where the Chatbot information is stored, for example if a word is received, which must be answered.<br>
•	**chatbot_database_with_data.sql**, database where the Chatbot information is stored, for example if a word is received, which must be answered. This database have information.<br>
•	**config.php**, global settings such as database connection details, WhatsApp tokens, and ChatGTP.<br>
•	**dbconextion.php**, database query functions.<br>
•	**webhook.php**, code that is responsible for handling all requests from WhatsApp.<br>




