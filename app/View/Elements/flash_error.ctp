<script>
$(document).ready(function(){
	$('.error').css('display', 'block');
	$('.error').text("<?php echo $message; ?>");
	$('.error').animate({top:"0"}, 1000);

	setTimeout(function() {
		$('.error').animate({top: -$(this).outerHeight()}, 1000);
	}, 5000);
});
</script>
