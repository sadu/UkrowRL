url = {};
pass = {};
success = {};
error = {};
url.template = function(id) {
	return '<div class="urlinput">URL: <input id="'+id+'" type="text" class="field" name="url" value="http://" onkeydown="this.size = this.value.length + 10;" /></div>';
}
pass.template = function(id) {
	return '<div class="passinput">Passphrase: <input id="'+id+'" type="password" class="field" name="pass" value="***" onkeydown="this.size = this.value.length + 10;" /></div>';
}
success.template = function(url) {
	return '<div class="success">Your short url is: <a href="'+url+'" class="link" >'+url+'</a></div>';
}
error.template = function(msg) {
	return '<div class="error">'+msg+'</div>';
}
function sendRequest(postData, place) {
	$.post('api.php', postData, function(data){
		data = JSON.parse(data);
		if(data.type == 'error')
			place.append(error.template(data.message));
		else
			place.append(success.template(data.message));
		initConsole(place);
	});
}
function initConsole(place){
	var offset = place.find('input[name="url"]').length;
	var postData = {};
	place.append(url.template('url' + offset));
	$('#url' + offset).focus().keypress(function(e) {
		if(e.which == 13) {
			postData.url = this.value;
			this.disabled = "disabled";
			place.append(pass.template('pass' + offset));
			$('#pass' + offset).focus().keypress(function(e) {
				if(e.which == 13) {
					this.disabled = "disabled";
					postData.pass = this.value;
					sendRequest(postData, place);
				}
			});
		}
	});
}
$(document).ready(function() {
	$('#noJS form').fadeOut('medium', function(){
		$('#yesJS').fadeIn('slow');
		initConsole($('#yesJS'));
	});
});