# Chained SMS

This CiviCRM extension enables you to conduct automated conversations via SMS, for example:

CiviCRM: Hello, How are you feeling today on a scale of 1 to 10 (1 = bad and 10 = good)
Contact: 3
CiviCRM: I'm sorry to hear that. Would you like a call to cheer you up?
Contact: Yes please
CiviCRM: OK - we'll have someone call you as soon as possible.

Chained SMS conversations start with an outbound SMS to a contact.

CiviCRM then listens for inbound SMS from the contact. When an inbound SMS is received, CiviCRM looks a the text of the message and, depending on the content of the message, decides to send another message, or to stop the conversation.

# Setting up a chain

1. Choose an initial message template to start the chain.
2. Create some expected answers for this message template.
3. For each answer, you can define responses to send when an inbound text matches the answer.




For example,

When an SMS is received The extension then listens to inbound SMS from a certain contact and sends

Chained SMS then listens for inbound SMS

It works by sending outbound SMS in response to inbound SMS that match a certain pattern. listening to inbound SMS and sending back outbound SMS when those  by allowing you to specify what outbSMS

 via  chains of outbound and inbound SMS.</Create automated chains of outbound and inbound SMS.</

* Outbound SMS can be sent in reply to inbound SMS.


## How
