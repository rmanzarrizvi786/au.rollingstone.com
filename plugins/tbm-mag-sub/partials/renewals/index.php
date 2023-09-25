<?php
wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css');
?>

<h1>Process Renewals</h1>
<button type="button" name="send-renewals-comp" id="send-renewals-comp" class="btn btn-info">Send Comps list</button>
<button type="button" name="send-overdue-invoices" id="send-overdue-invoices" class="btn btn-warning">Start/Pause Overdue Invoices</button>
<button type="button" name="process-renewals" id="process-renewals" class="btn btn-primary">Start/Pause Process renewals</button>
<span id="status-process"></span>
<table class="table table-sm" id="results"></table>
<script>
    jQuery(document).ready(function($) {
        var process = false;
        $('#process-renewals').on('click', function(e) {
            e.preventDefault();
            process = !process;
            toggleProcessStatus();
            if (process)
                processRenewals();
        });


        function processRenewals() {
            var fd = new FormData();
            fd.append('action', 'process_renewals');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function(res) {
                    // console.log(res);
                    if (res.success) {
                        $.each(res.data, function(i, e) {
                            if (e == 'Done') {
                                process = false;
                            }
                            $('#results').prepend('<tr><td>' + e + '</td></tr>');
                        });
                    } else {
                        // process = false;
                        $.each(res.data, function(i, e) {
                            $('#results').prepend('<tr><td class="bg-danger text-white">' + e + '</td></tr>');
                        });
                    }
                    if (process) {
                        processRenewals();
                    }
                    toggleProcessStatus();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    // alert(xhr.responseText);
                }
            });
        } // processRenewals()

        function toggleProcessStatus() {
            if (process) {
                // $('#process-renewals').text('Pause');
                $('#status-process').text('Processing...');
            } else {
                // $('#process-renewals').text('Start');
                $('#status-process').text('Paused');
            }
        } // toggleProcessStatus()

        $('#send-renewals-comp').on('click', function(e) {
            e.preventDefault();
            var btn = $(this);
            btn.attr('disabled', true);
            var fd = new FormData();
            fd.append('action', 'send_comps_renewals');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function(res) {
                    console.log(res);
                    if (res.success) {
                        alert(res.data);
                    } else {
                        alert(res.data);
                        btn.attr('disabled', false);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    // alert(xhr.responseText);
                }
            });
        });

        $('#send-overdue-invoices').on('click', function(e) {
            e.preventDefault();
            process = !process;
            toggleProcessStatus();
            if (process)
                processRenewalsOverdue();
        });

        function processRenewalsOverdue() {
            var fd = new FormData();
            fd.append('action', 'send_overdue_invoices');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.success) {
                        $.each(res.data, function(i, e) {
                            if (e == 'Done') {
                                process = false;
                            }
                            $('#results').prepend('<tr><td>' + e + '</td></tr>');
                        });
                    } else {
                        process = false;
                        $.each(res.data, function(i, e) {
                            $('#results').prepend('<tr><td class="bg-danger text-white">' + e + '</td></tr>');
                        });
                    }
                    if (process) {
                        processRenewalsOverdue();
                    }
                    toggleProcessStatus();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    // alert(xhr.responseText);
                }
            });
        } // processRenewalsOverdue()


    });
</script>