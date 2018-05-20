<footer class='text-center'>
    <div ng-controller="ConsentCtrl" class="consent-banner hide" ng-hide="consentGiven">
        This site uses Google Analytics to record pageviews. If you are not okay
        with that, then please stop vising this site.
        See our <a href="<?= get_privacy_policy_url(); ?>" target="_self">Privacy Policy</a>
        for more details.
        <button class="btn btn-outline-light" type="submit" ng-click="consent()">I Agree</button>
    </div>
    <ul>
        <li><a href="<?= site_url("terms-of-service"); ?>">Terms of Service</a></li>
        <li><a href="<?= site_url("privacy-policy"); ?>">Privacy Policy</a></li>
        <li><a href="<?= site_url("about"); ?>">About</a></li>
        <li>&copy;<?= date("Y"); ?></li>
    </ul>
</footer>
