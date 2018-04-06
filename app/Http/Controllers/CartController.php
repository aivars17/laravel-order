<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Http\Requests\StoreOrderRequest;
use App\Mail\OrderReceived;
use App\Order;
use App\OrderProduct;
use App\Product;
use App\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;
use App\Invoice;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    private $getTotal;

    public function __construct(CartService $cartService)
    {
        $this->getTotal = $cartService;
    }

    public function index()
    {
        $user = Auth::user();
        $order = $user->orders()->InCart()->Order()->first();
        $backorder = $user->orders()->InCart()->BackOrder()->first();
        $preorder = $user->orders()->InCart()->PreOrder()->first();
        $rejectedOrder = $user->orders()->RejectedOrder()->Order()->first();
        $rejectedBackorder = $user->orders()->RejectedOrder()->BackOrder()->first();
        $rejectedPreorder = $user->orders()->RejectedOrder()->Preorder()->first();
        if (!empty($order))
        {
            $order_products = $order->orderProducts()->get();
        }else{
            $order_products = [];
            $order = null;
        }
        if (!empty($backorder))
        {
            $backorders = $backorder->orderProducts()->get();

        }else{
            $backorders =[];
            $backorder = null;
        }
        if (!empty($preorder))
        {
            $preorders = $preorder->orderProducts()->get();
        }else{
            $preorders =[];
            $preorder = null;
        }
        if (!empty($rejectedOrder))
        {
            $rejectedOrders = $rejectedOrder->orderProducts()->get();
        }else{
            $rejectedOrders =[];
            $rejectedOrder = null;
        }
        if (!empty($rejectedBackorder))
        {
            $rejectedBackorders = $rejectedBackorder->orderProducts()->get();
        }else{
            $rejectedBackorders =[];
            $rejectedBackorder = null;
        }
        if (!empty($rejectedPreorder))
        {
            $rejectedPreorders = $rejectedPreorder->orderProducts()->get();
        }else{
            $rejectedPreorders =[];
            $rejectedPreorder = null;
        }

        return view('orders.single_basket', [
            'products' => $order_products,
            'order' =>$order,
            'backorder' => $backorder,
            'preorder' => $preorder,
            'backorders' => $backorders,
            'preorders' => $preorders,
            'rejectedPreorders' => $rejectedPreorders,
            'rejectedBackorders' => $rejectedBackorders,
            'rejectedOrders' => $rejectedOrders,
            'rejectedOrder' => $rejectedOrder,
            'rejectedBackorder' => $rejectedBackorder,
            'rejectedPreorder' => $rejectedPreorder,
        ]);
    }

    public function store($product_id, StoreOrderRequest $request)
    {
        $product = Product::findOrfail($product_id);

        if ($product->stockamount !== 0 && $product->preorder !== 1 && $product->preorder !== 2)
        {
            $amount = $this->getTotal->storeOrder($product, $request);
            if ($amount !== 0 )
            {
                $this->getTotal->storeBackOrder($product, $amount);
            }
        }elseif($product->stockamount === 0 && $product->preorder == 0){
            $this->getTotal->storeBackOrder($product, $request->quantity);
        } elseif($product->preorder === 1) {
            $this->getTotal->storePreOrder($product, $request->quantity);
        }
        $data = [
            'product_id'=>$product_id,
            'totalQuantity' => $this->getTotal->getUserOrderTotalQuantity(),
            'totalPrice' => $this->getTotal->getUserOrderTotalPrice(),
        ];

        return $data;
    }

    public function update($id, StoreOrderRequest $request)
    {
        $user = Auth::user();
        $product = OrderProduct::where('id', $id);
        if ($user->role === 'admin' && !empty($request->price))
        {
            $product->update([
                'price' => $request->price,
            ]);
            $product = OrderProduct::findOrFail($id);
            $singleProduct = $product;
            $data = ['id' => $id,
                'singleProductPrice' => $this->getTotal->getSingleProductPrice($singleProduct),
                'totalPrice' => $this->getTotal->getTotalCartPrice($singleProduct->order),
            ];
        }elseif($request->from == 'order'){
            if ($product->first()->product->stockamount >=  $request->quantity)
            {
                $data = $this->getTotal->updateOrder ($request->quantity, $product);
            }else{
                $product->update(['quantity' => $product->first()->product->stockamount]);
                $data = ['id' => $id,
                    'singleQuantity' => $product->first()->product->stockamount,
                    'true' => true,
                    'singlePrice' => $this->getTotal->getSingleProductPrice($product->first()),
                ];
            }
        }else{
            $data = $this->getTotal->updateOrder ($request->quantity, $product);
        }
        return $data;
    }

    public function destroy($id)
    {
        $order_product = OrderProduct::findOrFail($id);
        $order_product->delete();
        $order_products = $order_product->order->orderProducts()->get();
        if ($order_products->count() == 0){
            $order = Order::findOrFail($order_product->order_id);
            if ($order->chat()->count() != null)
            {
                foreach ($order->chat()->first()->messages as $message)
                {
                    $message->delete();
                }
                $order->chat()->delete();
            }
            $order->delete();
            return 'emptyOrder';
        }
        return 'Order';
    }

    public function confirm(Request $request)
    {
        $order = null;
        $backOrder = null;
        $preOrder = null;
        $orderComment = null;
        $rejectedOrder = null;
        $id = [];

        if ($request->has('order_id') or $request->has('rejectedOrder_id')) {
            if($request->has('order_id'))
            {
                $orders_id[] = $request->order_id;
            }
            if($request->has('rejectedOrder_id'))
            {
                $orders_id[] = $request->rejectedOrder_id;
            }
            for ($i = 0; count($orders_id) > $i ; $i++)
            {
                $order = Order::findOrFail($orders_id[$i]);
                $order->update(['status' => Order::UNCONFIRMED]);
                foreach ($order->orderProducts as $product) {
                    $stock = $product->product->stockamount;
                    $quantity = $stock - $product->quantity;
                    if ($quantity < 0) {
                        $quantity = 0;
                    }
                    $product->product->stock()->create(['amount' => $quantity]);
                }
                $id[] = $request->order_id;
            }
        }

        if ($request->has('backorder_id')) {
            $backOrder = Order::findOrFail($request->backorder_id);
            $backOrder->update(['status' => Order::UNCONFIRMED]);
            $id[] = $request->backorder_id;
        }

        if ($request->has('preorder_id')) {
            $preOrder = Order::findOrFail($request->preorder_id);
            $preOrder->update(['status' => Order::UNCONFIRMED]);
            $id[] = $request->preorder_id;
        }

        if (!empty($request->comments)) {
            for ($i = 0; count($id) > $i; $i++)
            {
                $chat = Chat::create(['order_id' => $id[$i], 'user_id' => Auth::id(),'topic' => 'Order nr. ' . $id[$i]]);
                $chat->messages()->create(['user_id' => Auth::id(), 'message' => $request->comments]);
            }
            $orderComment = $request->comments;
        }
        $userEmail = Auth::user()->client->email;
        Mail::to($userEmail)->send(new OrderReceived($order, $backOrder, $preOrder, $orderComment));
        return redirect()->back();
    }
}