$(function() {
	/**
	 * Used to send notification from three different pages. Added in order to minimize selfcoping.
	 */
	$('#send-push-link').fancybox({
		fitToView:true,
		autoSize:false,
		minWidth:300,
		minHeight:300,
		margin:20,
		padding:0,
		type:'ajax'
	});
});