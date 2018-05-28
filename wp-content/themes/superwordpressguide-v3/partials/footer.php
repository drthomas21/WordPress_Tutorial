<footer class='text-center'>
    <div ng-controller="ConsentCtrl" class="consent-banner hide" ng-hide="consentGiven">
        <div class="row">
            <div class="col-sm-12">
                This site uses Google Analytics to record pageviews and YouTube
                for videos, however, we do not collect personal data. By continuing to use our site,
                you are agreeing to our <a href="<?= site_url("/terms-of-service/"); ?>">Terms of Service</a>.
                <button class="btn btn-outline-success" type="submit" ng-click="consent()">I Understand</button>
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
