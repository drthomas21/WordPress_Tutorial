<div class="wrap">
    <h2>Personal Data Controls</h2>
    <div style="width: 20%; display:inline-block; position: relative">
        <button type="button" class="button" onclick="requestDataExport()">Request Data Export</button>
    </div>
    <div style="width: 20%; display:inline-block; position: relative">
        <button type="button" class="button" onclick="requestDataDeletion()">Request Data Deletion</button>
    </div>
</div>
<script type="text/javascript">
(function() {
    function sendRequest(type) {
        jQuery.ajax({
            url:"/wp-admin/admin-ajax.php",
            method: "POST",
            data: {
                action: "<?= $AJAX_ACTION; ?>",
                type: type
            }
        }).then(function(response) {
            alert(response.data.message);
        });
    }
    window.requestDataExport = function() {
        sendRequest(1);
    };

    window.requestDataDeletion = function() {
        sendRequest(2);
    };
})();
</script>
