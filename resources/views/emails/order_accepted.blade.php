<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Order Accepted</title>
</head>

<body style="font-family: Arial; background:#f4f6f8; padding:30px;">

<table width="100%" align="center" cellpadding="0" cellspacing="0">
<tr>
<td align="center">

<table width="600" style="background:white; border-radius:10px; overflow:hidden; box-shadow:0 5px 20px rgba(0,0,0,0.1);">

<!-- HEADER -->
<tr>
<td style="background:#28a745; color:white; padding:20px; text-align:center; font-size:24px; font-weight:bold;">
🌾 AgriConnect Marketplace
</td>
</tr>

<!-- CONTENT -->
<tr>
<td style="padding:30px;">

<div style="background:#e9f7ef; padding:15px; border-radius:6px; margin-bottom:20px;">
<b style=";">✅ Order Confirmed Successfully!</b>
</div>

<h2>Hello {{ $req->user->name }} 👋</h2>

<p style="font-size:16px;">
<p style="color:#777; font-size:14px;">
Order ID: #{{ $req->id }}
</p>
<p style="color:#888; font-size:13px;">
Placed on: {{ now()->format('d M Y, h:i A') }}
</p>
Your order request has been 
<span style="color:#28a745; font-weight:bold;">ACCEPTED 🎉</span>
</p>

<br>

<table width="100%" style="border-collapse:collapse; font-size:16px;">

<tr>
<td style="padding:12px; border-bottom:1px solid #ddd;">📦 <b>Product</b></td>
<td style="padding:12px; border-bottom:1px solid #ddd;">{{ $req->product->product_name }}</td>
</tr>

<tr>
<td style="padding:12px; border-bottom:1px solid #ddd;">🔢 <b>Quantity</b></td>
<td style="padding:12px; border-bottom:1px solid #ddd;">{{ $req->quantity }}</td>
</tr>

<tr>
<td style="padding:12px;">💰 <b>Total Price</b></td>
<td style="padding:12px; color:#28a745; font-weight:bold;">₹{{ $req->total_price }}</td>
</tr>

</table>

<div style="
background:#ffffff;
border:1px solid #e0e0e0;
padding:15px;
border-radius:8px;
margin-top:10px;
">
<b style="font-size:15px;">👨Farmer Contact Details</b><br><br>

<span style="font-size:14px;">
👨‍🌾 Name: {{ $req->product->farmer->name ?? 'Farmer' }}<br>
📱Phone: {{ $req->product->farmer->phone ?? 'Contact via platform' }}
</span>
</div>

<!-- BUTTON -->
<div style="display:flex; justify-content:center; margin-top:20px;">
    <a href="{{ url('/my-orders') }}" style="
        background:#28a745;
        color:white;
        padding:14px 35px;
        text-decoration:none;
        border-radius:6px;
        font-weight:bold;
        font-size:16px;
    ">
        View My Orders
    </a>
</div>


</td>
</tr>

<!-- FOOTER -->
<tr>
<td style="background:#f4f6f8; padding:20px; text-align:center; color:#999;">
Thank you for using <b>AgriConnect</b> 🌱<br>
<small>© {{ date('Y') }} AgriConnect Marketplace</small>
</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>