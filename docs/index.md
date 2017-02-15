# Chained SMS

Chained SMS is a CiviCRM extension that enables you to carry out automated conversations via SMS.

Conversations start with an outbound text from CiviCRM. Subsequent texts can then be sent dependent on the reply received to the first text. These couplets of outbound and inbound texts can be combined into longer chains (hence the name). Conversations can branch with different pathways based on the answers to previous questions.

## Examples

Here's a simple example:

> **CiviCRM:** Hello, do you plan to vote for Alice as the next prime minister? [Please answer 'yes', 'no', or 'maybe']

> **Contact:** yes

> **CiviCRM:** Thanks for letting us know!

Here's a more involved example where the second text we send depends on the answer to the first text.

> **CiviCRM:** Hello, are you interested in distributing placards to support Bob in the upcoming election? [Please answer 'yes' or 'no']

> // *If they answer '**yes**'*
>
> **Contact:** yes

> **CiviCRM:** Great, we can send you up to 20 placards. How many would you like? (please answer with a number)

> **Contact:** 3

> **CiviCRM:** OK - we'll get them sent to you as soon as possible. We may be in contact again to clarify your address and other details.

> // *OR if they answer '**no**'*

> **CiviCRM:** OK. Thanks anyway!

## Getting started

Chained SMS is easy to use. To get started, you'll need to [install](install.md) the extension, [create some message chains](create), and [test](test.md) them out. Once you are happy that the conversation is proceeding as desired, you can start the chain by [sending the first message](send) in the chain to either a contact or a group of contacts.
