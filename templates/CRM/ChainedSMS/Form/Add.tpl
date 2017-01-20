<div class="crm-form-block">
<div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
<table class="form-layout">
{if $action eq 8}
{* Action = delete *}

Are you sure you want to delete this couplet?

<tr><td class="label">Initial template ID</td><td class="view-value">{$initial_msg_template_for_delete}</td></tr>
<tr><td class="label">Answer</td><td class="view-value">{$answer_for_delete}</td></tr>
<tr><td class="label">Subsequent template ID</td><td class="view-value">{$subsequent_msg_template_for_delete}</td></tr>
{elseif $action eq 2 OR $action eq 1}

{* Action = update *}
<tr><td class="label">{$form.initial_msg_template.label}</td><td class="view-value">{$form.initial_msg_template.html}</td></tr>
<tr><td class="label">{$form.answer.label}</td><td class="view-value">{$form.answer.html}</td></tr>
<tr><td class="label">{$form.subsequent_msg_template.label}</td><td class="view-value">{$form.subsequent_msg_template.html}</td></tr>


{/if}
</table>
<div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
</div>
