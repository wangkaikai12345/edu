<div class="modal fade edit-modal" id="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" id="modal-size" role="document">
        <div class="modal-content" id="modal-content">
        </div>
    </div>
</div>
<script>
$(function(){
    $(document).on('click', '[data-toggle="modal"][data-target="#modal"]', function(e){
        if ($(this).data("url") === undefined) return false;
        $('#modal').addClass($(this).data('developer'));
        $('#modal-size').addClass(($(this).data('size') === undefined ? 'modal-lg' : $(this).data('size')));
        $($(this).data("target") + ' .modal-content').load($(this).data("url"));
    });
    $("#modal").on("hidden.bs.modal", function() {
        $('#modal .modal-content').html('');
    });
})
</script>
