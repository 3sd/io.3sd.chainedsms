# Creating chained SMS

Chained SMS convesations are built up of *couplets*.

## About couplets

A couplet is an outbound message, an inbound reply to the outbound message, and one or more Follow on outbound messages that can be sent in reply to the inbound message.  The messages are defined as message templates.

A couplet works as follows.

1. CiviCRM sends the initial outbound message to a contact
2. The contact (hopefully) replies to the outbound message.
3. CiviCRM reads the inbound message and checks it against a list of expected responses. If it finds a match, it sends the corresponding outbound message. If it does not find a match, the conversation will end.

### Example couplets

Here are some example couplets that could make up a conversation.

---

**Initial message**: Hello, are you interested in distributing placards to support Bob in the upcoming election? [Please answer 'yes' or 'no']

Expected reply | Follow on message
-------------- | ----------------------------------------
Yes            | Great, would you like 1 or 2 placards? [(please answer '1' or '2']
No             | OK - sorry to bother you!

---

**Initial message**: Great, would you like 1 or 2 placards? [(please answer '1' or '2']

Expected reply | Follow on message
-------------- | ---------------------------------------------
1              | OK - we'll send it to you as soon as possible. We may be in contact again to clarify any other details.
2              | OK - we'll send them to you as soon as possible. We may be in contact again to clarify any other details.
*              | Sorry, I didn't understand that. Someone will be in contact to clarify what you need soon.

---

### Wildcard responses

Note that you can defined an expected reply as `*` which will cause it to be sent when none of the other replies are matched.

## Creating a couplet

Before creating a couplet, you will need to define all the outbound messages as templates (see **Mailings > Message templates**). Once these have been defined, you can create a couplet by clicking on the 'add a new couplet' button. This will bring up a form that allows you to select an initial message, enter an expected reply, and select a follow on message. Repeat this process until all your couplets are defined.
