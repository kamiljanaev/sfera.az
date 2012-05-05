var backUrl = '<?= $this->msgBackUrl ?>'
$(function(){
 	$('.cancel-action').click(function(event){
 		event.preventDefault();
 		document.location = backUrl;
 	});
    $('.notActivated a').click(function(event) {
        event.preventDefault();
        if(!confirm('<?= $this->msgConfirmActivateItem ?>')){
            return false;
        }
        var id = $(this).attr('name');
        var url = $(this).attr('href');
        $.getJSON(
            url,
            jsonSuccessResponse
        )
    });
})
function jsonSuccessResponse(response) {
    window.location.reload();
}
