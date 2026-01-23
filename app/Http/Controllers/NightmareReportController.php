
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

public function nightmareReport(Request $request){

    // Normalize & validate inputs
    $from      = $request->input('from');
    $to        = $request->input('to');
    $email     = $request->input('q');
    $status    = $request->input('status', 'APPROVED');
    $sort      = $request->input('sort', 'created_at');
    $dir       = strtolower($request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
    $limit     = max((int) $request->input('limit', 50), 1);
    $providers = array_filter(explode(',', (string) $request->input('providers')));

    // Whitelist sortable columns to avoid SQL injection
    $sortableColumns = ['created_at', 'name', 'email'];
    if (! in_array($sort, $sortableColumns, true)) {
        $sort = 'created_at';
    }

    // Build base query
    $query = DB::table('users as u')
        ->select([
            'u.id',
            'u.name',
            'u.email',
            DB::raw('(select count(*) from orders o2 where o2.user_id = u.id) as orders_count'),
        ])
        ->leftJoin('orders as o', 'o.user_id', '=', 'u.id')
        ->leftJoin('payments as pay', 'pay.order_id', '=', 'o.id')
        ->leftJoin('shipments as sh', 'sh.order_id', '=', 'o.id')
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('o.created_at', [$from, $to]);
        })
        ->where(function ($q) use ($status) {
            $q->where('o.status', $status)
              ->orWhere('pay.status', $status);
        })
        ->when(! empty($providers), function ($q) use ($providers) {
            $q->whereIn('sh.provider_id', $providers);
        })
        ->when($email, function ($q) use ($email) {
            $q->where('u.email', 'like', "%{$email}%");
        })
        ->groupBy('u.id', 'u.name', 'u.email')
        ->orderBy($sort, $dir)
        ->limit($limit);

    $rows = $query->get();

    // Eager-load related models once
    $users = User::query()
        ->with([
            'orders.items.product.manufacturer',
            'orders.items.product.category',
            'orders.payments',
            'orders.shipment.address',
            'profile',
            'roles.permissions',
            'sessions',
        ])
        ->whereIn('id', $rows->pluck('id'))
        ->get()
        ->keyBy('id');

    // Build response
    $result = [];

    foreach ($rows as $row) {
        $user = $users->get($row->id);

        if (! $user) {
            continue;
        }

        $lastOrder = $user->orders->sortByDesc('created_at')->first();

        $paidTotal = $user->orders->sum('total');

        $result[] = [
            'id'             => $user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'last_order_at'  => optional($lastOrder)->created_at,
            'orders_count'   => (int) $row->orders_count,
            'paid_total'     => $paidTotal,
            'meta_json'      => json_encode([
                'vip'  => (bool) $request->boolean('vip'),
                'note' => (string) $request->input('note'),
            ]),
        ];
    }

    return response()->json($result);
}


// SUMMARY OF WHATS IMPROVED:
/* The refactor made the method safer, faster, and easier to reason about by eliminating
SQL injection risks through proper parameter binding and input whitelisting, fixing a
subtle logic bug in the status conditions, and removing unsafe raw SQL string interpolation.
It significantly improved performance by eliminating N+1 queries and loading all
related models in bulk, while also simplifying the query structure with clearer,
intention-revealing when() clauses. Finally, it replaced fragile manual JSON string 
construction with proper encoding, normalized and validated request inputs, and reorganized 
the method into clear logical sections, making future changes far less error-prone. */



// public function nightmareReport(Illuminate\Http\Request $request)
// {
// $from = $request->get('from');
// $to = $request->get('to');
// $email = $request->get('q');
// $status = $request->get('status', 'APPROVED');
// $sort = $request->get('sort', 'created_at');
// $dir = $request->get('dir', 'desc');
// $limit = (int)$request->get('limit');

// $query = DB::table('users as u')
// ->selectRaw("
// u.id,
// u.name,
// u.email,
// (select count(*) from orders o2 where o2.user_id = u.id) as orders_count,
// concat('{\"status\":\"', '{$status}', '\",\"email\":\"', '{$email}', '\"}') as debug_json
// ")
// ->leftJoin(
// 'orders as o',
// 'o.user_id',
// '=',
// 'u.id'
// )
// ->leftJoin(
// 'order_items as oi',
// 'oi.order_id',
// '=',
// 'o.id'
// )
// ->leftJoin(
// 'products as p',
// 'p.id',
// '=',
// 'oi.product_id'
// )
// ->leftJoin(
// 'manufacturers as m',
// 'm.id',
// '=',
// 'p.manufacturer_id'
// )
// ->leftJoin(
// 'categories as c',
// 'c.id',
// '=',
// 'p.category_id'
// )
// ->leftJoin(
// 'payments as pay',
// 'pay.order_id',
// '=',
// 'o.id'
// )
// ->leftJoin(
// 'shipments as sh',
// 'sh.order_id',
// '=',
// 'o.id'
// )
// ->leftJoin(
// 'addresses as a',
// 'a.id',
// '=',
// 'sh.address_id'
// )
// ->leftJoin(
// 'coupons as cp',
// 'cp.id',
// '=',
// 'o.coupon_id'
// )
// ->whereRaw("o.created_at >= '{$from}' and o.created_at <= '{$to}'")
// ->where('o.status', $status)
// ->orWhere('pay.status', $status)
// ->whereRaw("sh.provider_id in ({$request->get('providers')})");


// if ($email) {
// $query->whereRaw("u.email like '%{$email}%'");
// }

// $rows = $query
// ->orderByRaw("{$sort} {$dir}")
// ->groupByRaw("u.id, u.name, u.email")
// ->limit($limit)
// ->get();

// $result = [];

// foreach ($rows as $row) {
// $user = User::query()
// ->with([
// 'orders.items.product.manufacturer',
// 'orders.items.product.category',
// 'orders.payments',
// 'orders.shipment.address',
// 'profile',
// 'roles.permissions',
// 'sessions',
// ])
// ->find($row->id);

// $lastOrderDate = optional($user->orders->sortByDesc('created_at')->first())->created_at;

// $paidTotal = Order::query()
// ->where('user_id', $user->id)
// ->get()
// ->sum('total');

// $result[] = [
// 'id' => $user->id,
// 'name' => $user->name,
// 'email' => $user->email,
// 'last_order_at' => $lastOrderDate,
// 'orders_count' => $row->orders_count,
// 'paid_total' => $paidTotal,
// 'meta_json' => '{"vip":' . ($request->get('vip') ? 'true' : 'false') . ',"note":"' . $request->get('note') . '"}',
// ];
// }

// return response()->json($result);
// }