<script>
$(document).ready(function(){
	$('.success').css('display', 'block');
	$('.success').text("<?php echo $message; ?>");
	$('.success').animate({top:"0"}, 1000);

	setTimeout(function() {
		$('.success').animate({top: -$(this).outerHeight()}, 1000);
	}, 5000);
});
</script>
