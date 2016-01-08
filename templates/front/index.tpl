{ia_add_media files='css: _IA_URL_plugins/tell_a_friend/templates/front/css/style'}

<form action="" method="post" id="tell_friend" class="tell-a-friend">
	{preventCsrf}

	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="receiver_name">{lang key='receiver_name'}:
					<input class="form-control" type="text" name="receiver_name" id="receiver_name" value="{if isset($smarty.post.receiver_name)}{$smarty.post.receiver_name}{/if}">
				</label>
			</div>

			<div class="form-group">
				<label for="receiver_email">{lang key='receiver_email'}:
					<input class="form-control" type="text" name="receiver_email" id="receiver_email" value="{if isset($smarty.post.receiver_email)}{$smarty.post.receiver_email}{/if}">
				</label>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label for="sender_name">{lang key='sender_name'}:
					<input class="form-control" type="text" name="sender_name" id="sender_name" value="{if isset($smarty.post.sender_name)}{$smarty.post.sender_name}{/if}">
				</label>
			</div>

			<div class="form-group">
				<label for="sender_email">{lang key='sender_email'}:
					<input class="form-control" type="text" name="sender_email" id="sender_email" value="{if isset($smarty.post.sender_email)}{$smarty.post.sender_email}{/if}">
				</label>
			</div>
		</div>
	</div>

	<div class="form-group m-b-0">
		<label for="message">{lang key='message'}:
			<textarea class="form-control" name="message" id="message" rows="5">{if isset($smarty.post.message)}{$smarty.post.message}{/if}</textarea>
		</label>
		{ia_add_js}
			jQuery(function($)
			{
				$('#message').dodosTextCounter('500', {
						counterDisplayElement: 'span',
						counterDisplayClass: 'textcounter_message'
					});
				$('.textcounter_message').addClass('textcounter').wrap('<p class="help-block text-right m-b-0"></p>').before('{lang key='chars_left'} ');
			});
		{/ia_add_js}
		{ia_print_js files='jquery/plugins/jquery.textcounter'}
	</div>

	{include file="captcha.tpl"}

	<div class="form-actions">
		<input type="submit" class="btn btn-primary" value="{lang key='tell_a_friend'}">
	</div>
</form>