<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Invoice</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

</head>
<style>
    .table td, .table th {
    padding: 0.5rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
    font-size: 14px;
}
</style>
<body>

<div class="container col-12">
    <div class="row justify-content-center">
        <div class="col-sm-10">
            <div class="card">
                <div class="card-body">
				    <h3 class="text-center font-weight-bold mb-1">LLYOMAX</h3>
					<p class="text-center font-weight-bold"><small class="font-weight-bold">Phone No.:+91 9645 931 458</small></p>
                    <div class="row">
                        <div class="col-md-4">
                         <p class="mb-0"><strong>Order ID : </strong>: {{$order->order_id}}</p>
                         <p class="mb-0"><strong>Order Date</strong>:{{$order->order_date->format('F d, Y')}}</p>
                        </div>

                        <div class="col-md-6 ">
                         <p>
                            <address>
                                <strong>Shipped To:</strong><br>
                                    {{$order->customer->name}} <br>
                                    {{$order->customer->address}} <br>
                                    {{$order->customer->phone}} <br>
                                    {{$order->customer->email}} <br>
                                </address>
                         </p>
                        </div>
                    </div>
                    <div class="table-responsive">
					<table class="table table-bordered mb-0">
						<thead>
							<tr>
								<th class="text-uppercase small font-weight-bold">SR No.</th>
								<th class="text-uppercase small font-weight-bold">Item</th>
								<th class="text-uppercase small font-weight-bold">Price</th>
								<th class="text-uppercase small font-weight-bold">Qty</th>
								<th class="text-uppercase small font-weight-bold">Total</th>
							</tr>
						</thead>
						<tbody>
                            @php
                                $i=1;
                            @endphp

                            @foreach ($order->products as $product)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$product->product->name}}</td>
                                <td class="text-center">{{$product->product->mrp}}</td>
                                <td class="text-center">{{$product->qty}}</td>
                                <td class="text-right">{{$product->price}}</td>
                            </tr>
                            {{$i++}}
                            @endforeach

						</tbody>
						<tfoot class="font-weight-bold small">
						 <tr>
                            <td> </td>
                             <td> </td>
                             <td colspan="2">Sub Total</td>

							<td>{{$order->sub_amount}}</td>
						 </tr>
                         <tr>
                            <td> </td>
                             <td> </td>
                             <td colspan="2">Quantity</td>

							<td>{{$order->quantity}}</td>
						 </tr>
                         @isset($order->scheme_amount)
                         <tr>
                            <td> </td>
                             <td> </td>
                             <td colspan="2">Scheme amount</td>

							<td>{{$order->scheme_amount}}</td>
						 </tr>

                         @endisset
                         <tr>
                            <td> </td>
                             <td> </td>
                             <td colspan="2">Discount</td>

							<td>{{$order->discount ?? 0}}</td>
						 </tr>
						 <tr>
                            <tr>
                                <td> </td>
                                 <td> </td>
                                 <td colspan="2">Shipping Charge</td>

                                <td>{{$order->shipping_charge ?? 0}}</td>
                             </tr>
                             <tr>
                            <td> </td>
                            <td> </td>
                            <td colspan="2"> <strong>Total : </strong> </td>

							<td >{{$order->net_amount}}</td>
						  </tr>
						</tfoot>
                      </table>
                    </div><!--table responsive end-->
               </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
