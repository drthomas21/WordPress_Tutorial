<footer class='text-center'>
    <div ng-controller="ConsentCtrl" class="consent-banner hide" ng-hide="consentGiven">
        <div class="row">
            <div class="col-sm-12">
                This site uses <a href="https://tools.google.com/dlpage/gaoptout" target="_blank">Google Analytics</a> to record pageviews. Outside of that,
                we do not collect any information. By staying on this site, you are giving us your consent.
                <button class="btn btn-outline-light" type="submit" ng-click="consent()">I Agree</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                See our <a href="<?= get_privacy_policy_url(); ?>" target="_self">Privacy Policy</a> for more details.
            </div>
        </div>
    </div>
    <ul>
        <li><a href="<?= site_url("terms-of-service"); ?>">Terms of Service</a></li>
        <li><a href="<?= site_url("privacy-policy"); ?>">Privacy Policy</a></li>
        <li><a href="<?= site_url("about"); ?>">About</a></li>
        <li>&copy;<?= date("Y"); ?></li>
    </ul>
</footer>
