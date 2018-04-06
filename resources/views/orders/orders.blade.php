@extends('layouts.main', ['title' => 'Orders'])
@section('content')

	<div class="col-10 mt-5">
		<!-- Filters -->
		<div class="form-row align-items-center">
			<form class="form-inline" action="{{route('order.orders')}}" method="get">
				@admin
				<div class=" col-auto my-1">
					<select class="form-control" name="user_id" type="button"
					        class="btn btn-dark btn-sm dropdown-toggle filters" data-toggle="dropdown"
					        aria-haspopup="true" aria-expanded="false">
						<option value="-1">User name</option>
						@foreach($users as $user)
							<option {{($selectedUser == $user->id)?'selected="selected"':''}} value="{{$user->id}}">{{$user->name}}</option>
						@endforeach
					</select>
				</div>
				@endadmin
				<div class=" col-auto my-1">
					<select class="form-control" name="status" type="button"
					        class="btn btn-dark btn-sm dropdown-toggle filters" data-toggle="dropdown"
					        aria-haspopup="true" aria-expanded="false">
						<option value="-1">Status</option>
						@foreach(['Pending'=>App\Order::PENDING, 'Unconfirmed'=>App\Order::UNCONFIRMED, 'Confirmed'=>App\Order::CONFIRMED, 'Rejected'=>App\Order::REJECTED] as $key=>$value)
							<option {{($selectedStatus == $value)?'selected="selected"':''}} value="{{$value}}">{{$key}}</option>
						@endforeach
					</select>
				</div>
				<div class=" col-auto my-1">
					<select class="form-control" name="type" type="button"
					        class="btn btn-dark btn-sm dropdown-toggle filters" data-toggle="dropdown"
					        aria-haspopup="true" aria-expanded="false">
						<option value="-1">Type</option>
						@foreach(['Order'=>App\Order::ORDER, 'Pre-order'=>App\Order::PREORDER, 'Back-order'=>App\Order::BACKORDER] as $key=>$value)
							<option {{($selectedType == $value)?'selected="selected"':''}} value="{{$value}}">{{$key}}</option>
						@endforeach
					</select>
				</div>
				<button type="submit" name="filter" class="btn btn-danger filters">Filter</button>
			</form>
		</div>
		<!-- Order table -->
		<div class="row">
			<div class="col-md-12 table-responsive">
				<table class="table table-sm">
					<thead class="thead-light">
					<tr>
						<th scope="col">
							@if ($sortName == 'order_id' && $direction == 'asc')
								<a href="{{ route('orders.sort', ['name' => 'order_id', 'direction' => 'desc', 'query' => $query ]) }}">
									Order ID: <i class="fa fa-sort-up"></i>
								</a>
							@else
								<a href="{{ route('orders.sort', ['name' => 'order_id', 'direction' => 'asc', 'query' => $query ]) }}">
									Order ID: <i class="fa fa-sort-down"></i>
								</a>
							@endif
						</th>
						<th scope="col">
							@if ($sortName == 'date' && $direction == 'asc')
								<a href="{{ route('orders.sort', ['name' => 'date', 'direction' => 'desc', 'query' => $query ]) }}">
									Date: <i class="fa fa-sort-up"></i>
								</a>
							@else
								<a href="{{ route('orders.sort', ['name' => 'date', 'direction' => 'asc', 'query' => $query ]) }}">
									Date: <i class="fa fa-sort-down"></i>
								</a>
							@endif
						</th>
						<th scope="col">
							@if ($sortName == 'user_id' && $direction == 'asc')
								<a href="{{ route('orders.sort', ['name' => 'user_id', 'direction' => 'desc', 'query' => $query ]) }}">
									User name: <i class="fa fa-sort-up"></i>
								</a>
							@else
								<a href="{{ route('orders.sort', ['name' => 'user_id', 'direction' => 'asc', 'query' => $query ]) }}">
									User name: <i class="fa fa-sort-down"></i>
								</a>
							@endif
						</th>
						<th scope="col">
							@if ($sortName == 'status' && $direction == 'asc')
								<a href="{{ route('orders.sort', ['name' => 'status', 'direction' => 'desc', 'query' => $query ]) }}">
									Status: <i class="fa fa-sort-up"></i>
								</a>
							@else
								<a href="{{ route('orders.sort', ['name' => 'status', 'direction' => 'asc', 'query' => $query ]) }}">
									Status: <i class="fa fa-sort-down"></i>
								</a>
							@endif
						</th>
						<th scope="col">
							@if ($sortName == 'type' && $direction == 'asc')
								<a href="{{ route('orders.sort', ['name' => 'type', 'direction' => 'desc', 'query' => $query ]) }}">
									Type: <i class="fa fa-sort-up"></i>
								</a>
							@else
								<a href="{{ route('orders.sort', ['name' => 'type', 'direction' => 'asc', 'query' => $query ]) }}">
									Type: <i class="fa fa-sort-down"></i>
								</a>
							@endif
						</th>
						<th scope="col">Invoice:</th>
					</tr>
					</thead>
					<tbody>
					@foreach ($orders as $order)

						<tr>
							<td data-label="Order:" class="align-middle text-right text-lg-center"><a
										href="{{route('order.products', $order->id)}}">{{$order->id}} </a></td>
							<td data-label="Date:" class="align-middle text-right text-lg-center">{{$order->date}}</td>
							<td data-label="User name:"
							    class="align-middle text-right text-lg-center">{{$order->user->name}}</td>
							<td data-label="Status:"
							    class="align-middle text-right text-lg-center">{{$order->OrderStatus}}</td>
							<td data-label="Type:"
							    class="align-middle text-right text-lg-center">{{$order->OrderType}}</td>
							<td data-label="Invoice:" class="align-middle text-right text-lg-center">
								@if(!empty($order->invoice))
									<a href="{{ route('order.invoice.download', $order->id) }}"><img width="20px"
									                                                                 class="figure-img text-right text-lg-center"
									                                                                 src="images/pdf.png"></a>
								@endif
							</td>
						</tr>
					@endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Export all to Excel -->

    <div class="row">
        <div class="col-10">
            <label>
                <h4>Export all to excel:</h4>
            </label>
        </div>
        <div class="btn-group col-10" role="group" aria-label="export_buttons">
	        @if($selectedType == \App\Order::ORDER)
		        <a href="{{ route('export', 'order') }}" class="btn btn-danger btn-sm export mr-1">Orders</a>
			@elseif($selectedType == \App\Order::PREORDER)
		        <a href="{{ route('export', 'preorder') }}" class="btn btn-danger btn-sm export mr-1">Pre-orders</a>
			@elseif($selectedType == \App\Order::BACKORDER)
		        <a href="{{ route('export', 'backorder') }}" class="btn btn-danger btn-sm export mr-1">Back-orders</a>
			@else
		        <a href="{{ route('export', 'order') }}" class="btn btn-danger btn-sm export mr-1">Orders</a>
		        <a href="{{ route('export', 'preorder') }}" class="btn btn-danger btn-sm export mr-1">Pre-orders</a>
		        <a href="{{ route('export', 'backorder') }}" class="btn btn-danger btn-sm export mr-1">Back-orders</a>
			@endif




        </div>
    </div>
    <!-- Pagination -->
    <div id="pagination" class="row justify-content-center">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
            {{ $orders->links() }}
            </ul>
        </nav>
    </div>
	</div>
	</div>
@endsection