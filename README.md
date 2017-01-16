# Chained SMS

This CiviCRM extension enables you to carry out automated conversations via SMS.

Here is an example:

> **CiviCRM:** Hello, How are you feeling today on a scale of 1 to 10 (1 = bad and 10 = good)

> **Contact:** 3

> **CiviCRM:** I'm sorry to hear that. Would you like a call to cheer you up?

> **Contact:** Yes please

> **CiviCRM:** OK - we'll have someone call you as soon as possible.

Chained SMS conversations might be simple, consisting of a single question and answer. Or they might be more complex, with branches in the conversation depending on the answers received. Regardless of the level of complexity, all conversations can be broken down into a series of 'couplets'.

## Couplets - the building blocks of chained SMS

Each couplet starts with an outbound message to an intended recipient, which is followed (hopefully) by an inbound message from that recipient. CiviCRM reads the inbound message and decides, based on the content of the message, what to do next. It may decide to send another outbound message (which will start a new couplet) or it may decide to do nothing, i.e. to end the conversation.

In CiviCRM couplets are defined as an ***initial message***, along with a series of ***expected responses***, each of which has an associated ***subsequent message***.

Here are some example couples that make up a conversation.

### Example couplets

**Initial message**: Hello there, would you like to take part in a survey? [please answer yes or no]?

Expected reply | Subsequent message
-------------- | ----------------------------------------
No             | OK - sorry to bother you!
Yes            | OK, great! What is your favourite color?

**Initial message**: OK, great! What id your favourite color?

Expected reply | Subsequent message
-------------- | ---------------------------------------------
Blue           | What shade of blue?
Green          | Me too! Thanks for taking part in the survey!
*              | Thanks for taking part in the survey!

**Initial message**: What shade of blue?

Expected reply | Subsequent message
-------------- | -------------------------------------
*              | Thanks for taking part in the survey!

## Starting a conversation

To start a conversation, send an SMS based on a template to one or more contacts in CiviCRM.
