@extends('layouts.page', ['title' => 'Single basket'])
@section('content')
@inject('cartService', "App\Services\CartService")
<div class="col-10 mt-5">
		<!-- Order table -->
	@if(!empty($rejectedOrders))
		<div class="row">
			<div class="col-md-12 table-responsive">
				<h3>Rejected Order</h3>
				<table class="table table-sm">
					<thead class="thead-light">
					<tr>
						<th scope="col">EAN:</th>
						<th scope="col">Platform:</th>
						<th scope="col">Name:</th>
						<th scope="col">Release date:</th>
						<th scope="col">Publisher:</th>
						<th scope="col">Price:</th>
						<th scope="col">Price Total:</th>
						<th scope="col">Quantity</th>
						<th scope="col"></th>
					</tr>
					</thead>
					<tbody>
					@foreach($rejectedOrders as $rejectedOrderProduct)
						<tr>
							<td data-label="EAN:" class="align-middle text-right text-lg-center">{{ $rejectedOrderProduct->product->ean }}</td>
							<td data-label="Platform:" class="align-middle text-right text-lg-center">{{ $rejectedOrderProduct->product->platform->name }}</td>
							<td data-label="Name:" class="align-middle text-right text-lg-center">{{ $rejectedOrderProduct->product->name }}</td>
							<td data-label="Release date:" class="align-middle text-right text-lg-center">{{ $rejectedOrderProduct->product->release_date }}</td>
							<td data-label="Publisher:" class="align-middle text-right text-lg-center">{{ !empty($rejectedOrderProduct->product->publisher) ? $rejectedOrderProduct->product->publisher->name : '' }}</td>
							<td data-label="Price:" class="align-middle text-right text-lg-center">{{ number_format($rejectedOrderProduct->product->PriceAmount, 2, '.', '') }} €</td>
							<td id="singlePrice{{ $rejectedOrderProduct->id }}" data-label="Price:" class="align-middle text-right text-lg-center">{{ number_format($cartService->getSingleProductPrice($rejectedOrderProduct), 2, '.', '') }} €</td>
							<td data-label="Quantity:" class="align-middle text-right text-lg-center">
								<input data-url="{{ route('order.update',$rejectedOrderProduct->id) }}" class="input setquantity text-right" type="number" name="amount" value="{{ $rejectedOrderProduct->quantity }}" min="1">
								<br>
								<span id="message{{ $rejectedOrderProduct->id }}" ></span>
							</td>
							<td class="align-middle text-right text-lg-center">
								<button class="btn btn-danger btn-sm delete" data-html="{{ route('order.index') }}" data-url="{{ route('order.product.delete', $rejectedOrderProduct->id) }}">Delete</button>
							</td>
						</tr>
					@endforeach
					<tr>
						<td scope="total" colspan="6" class="text-right"><b>Total</b></td>
						<td class="align-middle text-right text-lg-center totalPrice" rowspan="6" data-label="Total">{{ !empty($rejectedOrders) ? $cartService->getTotalCartPrice($rejectedOrder) : ''}} €</td>
						<td class="align-middle text-right text-lg-center totalQuantity" data-label="Total quantity">{{ !empty($rejectedOrders) ? $cartService->getTotalCartQuantity($rejectedOrder) : '' }}</td>
						<td scope="total"></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	@endif
		@if(!empty($products))
		<div class="row">
			<div class="col-md-12 table-responsive">
				<h3>ORDER</h3>
				<table class="table table-sm">
					<thead class="thead-light">
						<tr>
							<th scope="col">EAN:</th>
							<th scope="col">Platform:</th>
							<th scope="col">Name:</th>
							<th scope="col">Release date:</th>
							<th scope="col">Publisher:</th>
							<th scope="col">Price:</th>
							<th scope="col">Price Total:</th>
							<th scope="col">Quantity</th>
							<th scope="col"></th>
						</tr>
					</thead>
					<tbody>
						@foreach($products as $product)
						<tr>
							<td data-label="EAN:" class="align-middle text-right text-lg-center">{{ $product->product->ean }}</td>
							<td data-label="Platform:" class="align-middle text-right text-lg-center">{{ $product->product->platform->name }}</td>
							<td data-label="Name:" class="align-middle text-right text-lg-center">{{ $product->product->name }}</td>
							<td data-label="Release date:" class="align-middle text-right text-lg-center">{{ $product->product->release_date }}</td>
							<td data-label="Publisher:" class="align-middle text-right text-lg-center">{{ !empty($product->product->publisher) ? $product->product->publisher->name : '' }}</td>
							<td data-label="Price:" class="align-middle text-right text-lg-center">{{ number_format($product->product->PriceAmount, 2, '.', '') }} €</td>
							<td id="singlePrice{{ $product->id }}" data-label="Price:" class="align-middle text-right text-lg-center">{{ number_format($cartService->getSingleProductPrice($product), 2, '.', '') }} €</td>
							<td data-label="Quantity:" class="align-middle text-right text-lg-center">
								<input data-url="{{ route('order.update',$product->id) }}" class="input setquantity text-right" type="number" name="amount" value="{{ $product->quantity }}" min="1">
								<br>
								<span id="message{{ $product->id }}" ></span>
							</td>
							<td class="align-middle text-right text-lg-center">
									<button class="btn btn-danger btn-sm delete" data-html="{{ route('order.index') }}" data-url="{{ route('order.product.delete', $product->id) }}">Delete</button>
							</td>
						</tr>
						@endforeach
						<tr>
							<td scope="total" colspan="6" class="text-right"><b>Total</b></td>
							<td class="align-middle text-right text-lg-center totalPrice" rowspan="6" data-label="Total">{{ !empty($products) ? $cartService->getTotalCartPrice($order) : ''}} €</td>
							<td class="align-middle text-right text-lg-center totalQuantity" data-label="Total quantity">{{ !empty($products) ? $cartService->getTotalCartQuantity($order) : '' }}</td>
							<td scope="total"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		@endif
		@if(!empty($backorders))
		<div class="row">
			<div class="col-md-12 table-responsive">
				<table class="table table-sm">
					<h3>BACK-ORDER</h3>
					<thead class="thead-light">
						<tr>
							<th scope="col">EAN:</th>
							<th scope="col">Platform:</th>
							<th scope="col">Name:</th>
							<th scope="col">Release date:</th>
							<th scope="col">Publisher:</th>
							<th scope="col">Price:</th>
							<th scope="col">Price Total:</th>
							<th scope="col">Quantity</th>
							<th scope="col"></th>
						</tr>
					</thead>
					<tbody>
						@foreach($backorders as $B_product)
						<tr>
							<td data-label="EAN:" class="align-middle text-right text-lg-center">{{ $B_product->product->ean }}</td>
							<td data-label="Platform:" class="align-middle text-right text-lg-center">{{ $B_product->product->platform->name }}</td>
							<td data-label="Name:" class="align-middle text-right text-lg-center">{{ $B_product->product->name }}</td>
							<td data-label="Release date:" class="align-middle text-right text-lg-center">{{ $B_product->product->release_date }}</td>
							<td data-label="Publisher:" class="align-middle text-right text-lg-center">{{ !empty($B_product->product->publisher) ? $B_product->product->publisher->name : '' }}</td>
							<td data-label="Price:" class="align-middle text-right text-lg-center">{{ number_format($B_product->product->PriceAmount, 2, '.', '') }} €</td>
							<td id="singlePrice_B{{ $B_product->id }}" data-label="Price:" class="align-middle text-right text-lg-center">{{ number_format($cartService->getSingleProductPrice($B_product), 2, '.', '') }} €</td>
							<td data-label="Quantity:" class="align-middle text-right text-lg-center">
								<input data-index="B" min="1" data-url="{{ route('order.update',$B_product->id) }}" class="input setquantity_BP text-right" type="number" name="amount" value="{{ $B_product->quantity }}">
								<br>
								<span id="message{{ $B_product->id }}" ></span>
							</td>
							<td class="align-middle text-right text-lg-center">
								<button class="btn btn-danger btn-sm delete" data-html="{{ route('order.index') }}" data-url="{{ route('order.product.delete', $B_product->id) }}">Delete</button>
							</td>
						</tr>
						@endforeach
						<tr>
							<td scope="total" colspan="6" class="text-right"><b>Total</b></td>
							<td class="align-middle text-right text-lg-center" id="totalPrice_B" rowspan="6" data-label="Total">{{ !empty($backorders) ? number_format($cartService->getTotalCartPrice($backorders->first()->order), 2, '.', '') : ''}} €</td>
							<td class="align-middle text-right text-lg-center" id="totalQuantity_B" data-label="Total quantity">{{ !empty($backorders) ? $cartService->getTotalCartQuantity($backorders->first()->order) : '' }}</td>
							<td scope="total"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		@endif
		@if(!empty($preorders))
		<div class="row">
			<div class="col-md-12 table-responsive">
				<table class="table table-sm">
					<h3>PRE-ORDER</h3>
					<thead class="thead-light">
						<tr>
							<th scope="col">EAN:</th>
							<th scope="col">Platform:</th>
							<th scope="col">Name:</th>
							<th scope="col">Release date:</th>
							<th scope="col">Publisher:</th>
							<th scope="col">Price:</th>
							<th scope="col">Price Total:</th>
							<th scope="col">Quantity</th>
							<th scope="col"></th>
						</tr>
					</thead>
					<tbody>
						@foreach($preorders as $P_product)
						<tr>
							<td data-label="EAN:" class="align-middle text-right text-lg-center">{{ $P_product->product->ean }}</td>
							<td data-label="Platform:" class="align-middle text-right text-lg-center">{{ $P_product->product->platform->name }}</td>
							<td data-label="Name:" class="align-middle text-right text-lg-center">{{ $P_product->product->name }}</td>
							<td data-label="Release date:" class="align-middle text-right text-lg-center">{{ $P_product->product->release_date }}</td>
							<td data-label="Publisher:" class="align-middle text-right text-lg-center">{{ !empty($P_product->product->publisher) ? $P_product->product->publisher->name : '' }}</td>
							<td data-label="Price:" class="align-middle text-right text-lg-center">{{ number_format($P_product->product->PriceAmount, 2, '.', '') }} €</td>
							<td id="singlePrice_P{{ $P_product->id }}" data-label="Price:" class="align-middle text-right text-lg-center">{{ number_format($cartService->getSingleProductPrice($P_product), 2, '.', '') }} €</td>
							<td data-label="Quantity:" class="align-middle text-right text-lg-center">
								<input data-index="P" min="1" data-url="{{ route('order.update',$P_product->id) }}" class="input setquantity_BP text-right" type="number" name="amount" value="{{ $P_product->quantity }}">
								<br>
								<span id="message{{ $P_product->id }}" ></span>
							</td>
							<td class="align-middle text-right text-lg-center">
								<button class="btn btn-danger btn-sm delete" data-html="{{ route('order.index') }}" data-url="{{ route('order.product.delete', $P_product->id) }}">Delete</button>
							</td>
						</tr>
						@endforeach
						<tr>
							<td scope="total" colspan="6" class="text-right"><b>Total</b></td>
							<td class="align-middle text-right text-lg-center" id="totalPrice_P" rowspan="6" data-label="Total">{{ !empty($preorders) ? number_format($cartService->getTotalCartPrice($preorders->first()->order), 2, '.', '') : ''}} €</td>
							<td class="align-middle text-right text-lg-center" id="totalQuantity_P" data-label="Total quantity">{{ !empty($preorders) ? $cartService->getTotalCartQuantity($preorders->first()->order) : '' }}</td>
							<td scope="total"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		@endif
		<div>
			@if(!empty($product))
			<input type="hidden" name="order_id" value="{{$order->id}}">
			@endif
			@if(!empty($backorder))
			<input type="hidden" name="backorder_id" value="{{$backorder->id}}">
			@endif
			@if(!empty($preorder))
			<input type="hidden" name="preorder_id" value="{{$preorder->id}}">
			@endif
			@if(!empty($rejectedOrder))
				<input type="hidden" name="$rejectedOrder_id" value="{{$rejectedOrder->id}}">
			@endif
			@if(!empty($product) || !empty($backorder) || !empty($preorder) || !empty($rejectedOrder))
			<div class="row mb-2">
				<div class="col-12">
					<a class="btn btn-dark btn-block" href="{{ route('home') }}">Back to Shop</a>
				</div>
			</div>
			@else
			<h3 class="text-center">Your cart is empty</h3>
			<a class="btn btn-dark btn-block" href="{{ route('home') }}">Back to Shop</a>
			@endif
		</div>
	<!-- Comments and attachments -->
	@if(!empty($order) || !empty($backorder) || !empty($preorder) || !empty($rejectedOrder))
	<div class="row">
		<div class="col-12 mt-2">
			<form action="{{ route('cart.confirm') }}" method="post">
				@csrf
				<div class="form-group">
					<label for="exampleFormControlTextarea1"><h3>Comments</h3></label>
					<textarea class="form-control" id="exampleFormControlTextarea1" name="comments" rows="6"></textarea>
				</div>
				@if(!empty($product))
				<input type="hidden" name="order_id" value="{{$order->id}}">
				@endif
				@if(!empty($backorder))
				<input type="hidden" name="backorder_id" value="{{$backorder->id}}">
				@endif
				@if(!empty($preorder))
				<input type="hidden" name="preorder_id" value="{{$preorder->id}}">
				@endif
				@if(!empty($rejectedOrder))
					<input type="hidden" name="rejectedOrder_id" value="{{$rejectedOrder->id}}">
				@endif
				<div class="form-group">
					<button type="submit" class="btn btn-danger btn-block" >Confirm your order</button>
				</div>
			</form>
		</div>
	</div>
	@endif
</div>
</div>
@endsection