<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;max-width:700px;border:2px solid #f3f3f3;text-align:left;font-family:sans-serif;">
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="width:100%!important">
                <tr>
                    <td width="100%" style="vertical-align:top;text-align:left;padding:15px 20px 10px;border-bottom:5px solid #81489c">
                        <a href="{{ env('APP_URL') }}" target="_blank">
                            <img src="{{ env('APP_URL') }}/assets/images/site-logo.svg" alt="{{ env('APP_NAME') }}" style="width:150px;"/>
                        </a>
                    </td>
                </tr>
                <tr>
                    @if($type == "admin")
                        <td style="padding:20px;font-size:14px;line-height:20px;">Hi Admin,<br/>{!! $user_name !!} has been placed an order. Below are the details of order:</td>
                    @else
                        <td style="padding:20px;font-size:14px;line-height:20px;">Hi {!! $name !!},<br/>We are pleased to confirm that your order has been successfully placed. Below are the details of your order:</td>
                    @endif
                </tr>
                <tr>
                    <td style="padding:0 20px 20px;">
                        <table style="width:100%;border-collapse:collapse;" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <td style="text-align:left;font-size:15px;font-weight:600;">Order Id #{!! $order['id'] !!}</td>
                                    <td style="text-align:right;font-size:15px;font-weight:600;">Order Date: {!! $order['created_at'] !!}</td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 20px 20px;">
                        <table style="width:100%;border-collapse:collapse;" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <th style="text-align:left;font-size:16px;font-weight:600;padding-right:15px;">Billing Information</th>
                                    <th style="text-align:left;font-size:16px;font-weight:600;padding-left:15px;">Shipping Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding-top:5px;text-align:left;font-size:14px;padding-right:15px;">{!! $order['billing_name'] !!}<br>
                                    {!! $order['billing_street_address'] !!}<br>
                                    {!! $order['billing_city'] !!}, {!! $order['billing_state'] !!} {!! $order['billing_zipcode'] !!}</td>
                                    <td style="padding-top:5px;text-align:left;font-size:14px;padding-left:15px;">{!! $order['shipping_name'] !!}<br>
                                    {!! $order['shipping_street_address'] !!}<br>
                                    {!! $order['shipping_city'] !!}, {!! $order['shipping_state'] !!} {!! $order['shipping_zipcode'] !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 20px 10px;font-size:20px;">Order Items</td>
                </tr>
                <tr>
                    <td style="padding:0 20px;">
                        <table style="width:100%;border-collapse:collapse;border:1px solid #ddd;" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <th style="padding:8px;text-align:left;background:#f9ebff;">Product</th>
                                    <th style="padding:8px;text-align:left;background:#f9ebff;">Price</th>
                                    <th style="padding:8px;text-align:left;background:#f9ebff;">Quantity</th>
                                    <th style="padding:8px;text-align:left;background:#f9ebff;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($order['orderItems']))
                                    @foreach($order['orderItems'] as $orderItem)
                                        <tr>
                                            <td style="padding:8px;text-align:left;font-size:14px;border-bottom:1px solid #ddd;">
                                                <div style="display:flex;align-items:center;column-gap:10px;">
                                                    <img src="{!! $orderItem['product']['image'] !!}" alt="{!! $orderItem['product']['name'] !!}" style="width:40px;height:40px;display:flex;"/>
                                                    <div style="display:flex;flex-direction:column;">
                                                        <div style="font-weight:500;font-size:16px;color:#3A3A3A;line-height:26px;text-align:left;">{!! $orderItem['product']['name'] !!}</div>
                                                        <div style="font-weight:500;font-size:16px;color:#3A3A3A;line-height:26px;text-align:left;">{!! $orderItem['product']['catalog_number'] !!}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding:8px;text-align:left;font-size:14px;border-bottom:1px solid #ddd;">{!! $orderItem['price'] !!}</td>
                                            <td style="padding:8px;text-align:left;font-size:14px;border-bottom:1px solid #ddd;">{!! $orderItem['quantity'] !!}</td>
                                            <td style="padding:8px;text-align:left;font-size:14px;border-bottom:1px solid #ddd;">{!! $orderItem['total'] !!}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td style="text-align:right;padding:8px;font-size:14px;">Subtotal:</td>
                                    <td style="padding:8px;font-size:14px;">${!! $order['subtotal'] !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td style="text-align:right;padding:8px;font-size:14px;border-top:1px solid #ddd;">Shipping:</td>
                                    <td style="padding:8px;font-size:14px;border-top:1px solid #ddd;">${!! $order['shipping_amount'] !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td style="text-align:right;padding:8px;font-size:14px;border-top:1px solid #ddd;">Tax:</td>
                                    <td style="padding:8px;font-size:14px;border-top:1px solid #ddd;">${!! $order['tax'] !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td style="text-align:right;font-size:15px;padding:8px;border-top:1px solid #ddd;font-weight:600;">Order Total:</td>
                                    <td style="padding:8px;font-size:15px;border-top:1px solid #ddd;font-weight:600;">${!! $order['total'] !!}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
                @if(($type != "admin") && ($order['send_invoice_me'] == '1'))
                    <tr>
                        <td style="padding:20px;font-size:14px;line-height:20px;">You will receive an invoice from Kimia to complete the payment for this purchase.</td>
                    </tr>
                @endif
                <tr>
                    <td style="padding:20px;font-size:14px;line-height:20px;">Thank you for shopping with us. If you have any questions regarding your order, feel free to contact us.</td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td colspan="2" style="color:#626262;font-size:12px;padding:5px 6px 5px 6px;text-align:center;background:#f9f9f9">Copyright &copy;{{ date('Y') }} {{ env('APP_NAME') }} | All rights Reserved.</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>