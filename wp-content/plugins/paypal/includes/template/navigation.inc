<div class="paypal-shopping-chart" ng-controller="ShoppingChartCtrl">
	<div class="error messages" ng-hide="issues.length == 0">
		<div class="alert alert-danger text-center" role="alert" ng-repeat="issue in issues">{{issue}}</div>
	</div>
	<div class="success messages"></div>
	<div class="warning messages"></div>
	<div class="shopping-chart pull-right col-lg-2" ng-hide="issues.length > 0 || items.length == 0">
		<button class="btn btn-default btn-circle btn-lg" ng-click="showShoppingChart()">
			<span class="button-count">{{items.length}}</span>
			<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
		</button>
	</div>
	
	<div class="modal fade" id="shoppingChartModal" role="dialog" aria-labelledby="Shopping Chart">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Shopping Chart</h4>
	      </div>
	      <div class="modal-body">
	      	<section ng-show="step == 0 || !step">
		        <table class="table table-stripped">
		        	<tr ng-repeat="object in items">
		        		<td class="col-xs-3"><img ng-src="{{object.itemImage}}" class="img-responsive" style="max-height: 100px"/></td>
		        		<td class="col-xs-3">
		        			<h4>{{object.itemName}}</h4>
	        				<a ng-href="{{object.itemUrl}}">source</a>
		        		</td>
		        		<td class="col-xs-3">{{price | currency:"$"}}</td>
		        		<td class="col-xs-3">
		        			<button ng-click="removeItem($index)"><span class="glyphicon glyphicon-trash"></span></button>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td class="text-right" colspan="4">
		        			<div class="pull-left"><img src="<?php echo plugin_dir_url(dirname(__DIR__))?>assets/paypal-full-logo.png" class="img img-responsive"/></div>
		        			<strong>Total: </strong> {{items.length * price | currency:"$"}}
		        		</td>
		        	</tr>
		        </table>
	        </section>
	        <section ng-show="step == 1">
	        	<form action="/checkout" method="POST" class="form">
	        		<div class="form-group" ng-repeat="attr in getUserProps()" ng-class="attr.className">
	        			<label ng-bind="attr.label" for="{{attr.id}}" class="control-label"></label>
	        			<input type="{{attr.type}}" ng-model="attr.value" name="{{attr.id}}" id="{{attr.id}}" ng-change="attr.sanitize()" ng-blur="attr.validation()" class="form-control"/>
	        		</div>
	        	</form>
	        </section>
	      </div>
	      <div class="modal-footer">
      		<button type="button" class="btn btn-default" ng-click="step = 0" ng-show="step == 1">Back</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		<button type="button" class="btn btn-primary" ng-click="step = 1" ng-show="(step == 0 || !step) && items.length > 0">Checkout</button>
      		<button type="button" class="btn btn-primary" ng-click="checkout()" ng-show="step == 1">Payment</button>	        
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>