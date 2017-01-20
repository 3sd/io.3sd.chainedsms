<p>SMS chains are made up of a series of SMS couplets. A couplet is an initial outgoing message, an expected reply, and a subseqent message. This page shows all existing SMS couplets, grouped by the initial outgoing message.  You can edit or delete existing couplets and add new ones.</p>
<p>The messages themselves are based on message templates. Visit the <a href="{crmURL p="civicrm/admin/messageTemplates" q="selectedChild=user&reset=1"}">message templates</a> page to edit and create new messages to use as the basis for your chained SMS.</p>
{foreach from=$templates item=template}
	<h3>{$template.cimt_msg_title}</h3>
	<p>{$template.cimt_msg_text}</p>
	<table class="display">
	<thead><tr><th>Reply</th><th>Subsequent message</th><th>Actions</th></tr></thead>
	<tbody>
	{foreach from=$template.answers item=answer}
		<tr class="{cycle values='odd,even'}-row">
			<td>{$answer.ccc_answer}</td>
			<td>
				<div><b>{$answer.csmt_msg_title}</b></div>
				<div>{$answer.csmt_msg_text}</div>
			</td>
			<td>
				<a href="{crmURL p="civicrm/sms/chains/answer" q="action=update&id=`$answer.ccc_id`"}">edit</a>
				<a href="{crmURL p="civicrm/sms/chains/answer" q="action=delete&id=`$answer.ccc_id`"}">delete</a>

			</td>
		<tr>
	{/foreach}
	</tbody>
	</table>
	<br />
{foreachelse}
<p>There are currently no couplets set up.
{/foreach}
<div class="crm-submit-buttons"><a class="add button" href="{crmURL p="civicrm/sms/chains/answer" q="action=add&msg_template_id=`$template.cimt_id`"}">add a new couplet</a></div>
