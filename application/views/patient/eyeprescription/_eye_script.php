
<script type="text/javascript">
    $(document).on('click', '.view_eye_prescription', function () {
        var $this = $(this);
        var record_id = $this.data('recordId');
        $this.button('loading');
        $.ajax({
            url: '<?php echo base_url(); ?>patient/eyeprescription/getPrescription',
            type: "POST",
            data: {id: record_id},
            dataType: 'json',
            success: function (data) {
                if (data.status == 1) {
                    $('#prescriptionview').modal({backdrop: 'static'});
                    $('#getdetails_prescription').html(data.page);
                } else {
                    alert(data.msg);
                }
                $this.button('reset');
            },
            error: function () {
                $this.button('reset');
            }
        });
    });
</script>
