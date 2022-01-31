# Simple example of MVC 

## Shopping Cart example

This example demonstrates the model-controller-view (MVC) pattern to separate the main concerns of the web application. Note there are many variants of MVC pattern, Generally controllers are usually minimal (thin controllers) and models handle almost all logic (fat models).

# Model
This is the main component. It handles the data and logic and rules of the application.

# Controller
This component controls a user request, and manages the model and view.
* Handles the incoming requests
* Validates requests (are requests bona fide?)
* Passes valid requests to the model. 
* Receives data from the model, and passes the data to the view.

# View
Presents the data received (from the controller) to the application (e.g. outputs HTML which is sent to user's browser).
