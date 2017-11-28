<?php
	wp_print_scripts('angular');
	wp_print_scripts('angular-sanitize');
?>
<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__DIR__));?>/assets/admin.js"></script>
<style type="text/css">
	.alert {
	    padding: 15px;
	    margin-bottom: 20px;
	    border: 1px solid transparent;
	    border-radius: 4px;
	}
	.alert-danger {
	    color: #a94442;
	    background-color: #f2dede;
	    border-color: #ebccd1;
	}
	
	.alert-success {
	    color: #3c763d;
	    background-color: #dff0d8;
	    border-color: #d6e9c6;
	}
</style>
<div class="wrapper" ng-app="paypal" ng-cloak>	
	<h2><img src="<?php echo plugin_dir_url(dirname(__DIR__));?>/assets/paypal-text.png" /></h2>
	<?php if(!empty($success)):?>
		<div class="alert alert-success"><?php echo $success; ?></div>
	<?php endif;?>
	<?php if(!empty($error)):?>
		<div class="alert alert-danger"><?php echo $error; ?></div>
	<?php endif;?>
	<form action="options.php" method="POST">
		<table>
			<tbody>
				<tr>
					<td><strong>API Client ID</strong></td>
					<td colspan="2">
						<input type="text" name="<?php echo self::OPTIONS_API_CLIENT_ID; ?>" value="<?php echo get_option(self::OPTIONS_API_CLIENT_ID); ?>" />
					</td>
				
					<td><strong>API Secrete</strong></td>
					<td colspan="2">
						<input type="text" name="<?php echo self::OPTIONS_API_SECRET; ?>" value="<?php echo get_option(self::OPTIONS_API_SECRET); ?>" />
					</td>
				
					<td><strong>API Sandbox Account</strong></td>
					<td colspan="2">
						<input type="text" name="<?php echo self::OPTIONS_API_SANDBOX_ACCOUNT; ?>" value="<?php echo get_option(self::OPTIONS_API_SANDBOX_ACCOUNT); ?>" />
					</td>
					
					<td><strong>Price</strong></td>
					<td colspan="2">
						<input type="number" step="any" name="<?php echo self::OPTIONS_PRICE; ?>" value="<?php echo floatval(get_option(self::OPTIONS_PRICE,1.00));?>" />
					</td>
				</tr>
			</tbody>			
		</table>
		<?php settings_fields(self::OPTIONS_SETTINGS_FIELD); ?>
		<?php submit_button("Update Settings");?>
	</form>
	<hr />
	<div ng-controller="SearchCtrl">
		<h2>Search for Orders</h2>
		<input type="text" ng-model="search"/><button ng-click="doSearch()">Search</button>
		<table ng-show="rows.length > 0" border="1">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Items</th>
					<th>Info</th>
					<th>Payment Number</th>
					<th>Status</th>
					<th>Order date</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="row in rows">
					<td>{{row.id}}</td>
					<td ng-bind-html="row.items"></td>
					<td>{{row.first_name}} {{row.last_name}}</td>
					<td ng-bind-html="row.info">{{row.info}}</td>
					<td>{{row.invoice_number}}</td>
					<td>{{row.status}}</td>
					<td>{{row.order_date}}</td>
					<td ng-bind-html="row.actions"></td>
				</tr>
			</tbody>
		</table>
	</div>
	<hr />		
	<div>
		<h2>Recent Orders</h2>
		<?php if(empty($recentOrders)):?>
			<i>Looks like no one has order anything</i>
		<?php else:?>
			<table border="1">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Items</th>
						<th>Info</th>
						<th>Payment Number</th>
						<th>Status</th>
						<th>Order date</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($recentOrders as $i=>$order):?>
					<tr>
						<td><?php echo $order->id?></td>
						<td><?php echo $order->first_name." ".$order->last_name;?></td>
						<td><?php echo self::getItemNames($order->items);?></td>
						<td><?php echo self::getOrderInfo($order);?></td>
						<td><?php echo $order->invoice_number;?>
						<td><?php echo $order->status;?></td>
						<td><?php echo $order->order_date;?></td>
						<td><?php echo self::getOrderActions($order);?></td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		<?php endif;?>
	</div>
		
	
	
	