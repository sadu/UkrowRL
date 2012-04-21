$(function() {
	$('#url-shortner').submit(function(e) {
		$form = $(this).children("form");
		$form.children("input#no-js").val(false);		
		sendRequest($form.serialize(), this);
        e.preventDefault();
	
    });
});
function sendRequest(postData, sender) {
	$.post('api.php', postData, function(data){
		data = JSON.parse(data);	
		if(data.error == '')
			$(sender).append('<div class="success">Your short url is: <a href="'+ROOT_PAGE+'index.php?key='+data.url+'" class="link" >'+ROOT_PAGE+'index.php?key='+data.url+'</a></div>');
		else
			$(sender).append('<div class="fail">An error occured: '+data.error+'</div>');
	});
}
