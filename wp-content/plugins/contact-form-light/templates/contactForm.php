<form action="javascript:void(0)" class="form" ng-app="contactApp" ng-controller="FormController">
    <div class="alert alert-success" role="alert" ng-repeat="message in messages">{{message}}</div>
    <div class="alert alert-danger" role="alert" ng-repeat="error in errors">{{error}}</div>

    <?php do_action(self::ACTION_FORM_TOP); ?>

    <div class="form-group">
        <label for="form-email">Email address </label>
        <input type="from" class="form-control" id="form-email" ng-model="form.from" placeholder="Enter Email">
    </div>
    <div class="form-group">
        <label for="form-subject">Subject</label>
        <input type="text" class="form-control" id="from-subject" ng-model="form.subject" placeholder="Enter Subject">
    </div>
    <div class="form-group">
        <label for="form-subject">Message</label>
        <textarea name="body" class="form-control" ng-model="form.body"></textarea>
    </div>

    <?php do_action(self::ACTION_FORM_BOTTOM); ?>

    <button class="btn btn-primary" ng-click="submitForm()">Submit</button>
</form>
