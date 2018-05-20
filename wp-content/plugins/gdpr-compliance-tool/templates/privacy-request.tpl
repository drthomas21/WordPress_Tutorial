<div class="wrap">
    <h2>Personal Data Controls</h2>
    <div id="gdpr-message" class="message"></div>
    <div style="margin-right: 50px; display:inline-block; position: relative">
        <button type="button" class="button" onclick="requestDataExport()">Request Data Export</button>
    </div>
    <div style="margin-right: 50px; display:inline-block; position: relative">
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
                type: type,
                "user_id": <?= $User->ID; ?>
            }
        }).then(function(response) {
            var styling = {
                "width": "auto",
                "padding": "5px 10px",
                "background-color": "#fff",
                "color": "#000",
                "margin-top": "5px",
                "margin-bottom": "10px",
                "display": "inline-block",
                "font-size": "16px"
            };
            if(!response.success) {
                styling['background-color'] = "#f17676";
            } else {
                styling['background-color'] = "#69d64e";
            }

            jQuery("#gdpr-message").html("<div>"+response.message+"</div>");
            jQuery("#gdpr-message div").css(styling);
        },function(response) {
            var styling = {
                "width": "auto",
                "padding": "5px 10px",
                "background-color": "#f17676",
                "color": "#000",
                "margin-top": "5px",
                "margin-bottom": "10px",
                "display": "inline-block",
                "font-size": "16px"
            };

            jQuery("#gdpr-message").html("<div>Failed to submit request</div>");
            jQuery("#gdpr-message div").css(styling);
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
