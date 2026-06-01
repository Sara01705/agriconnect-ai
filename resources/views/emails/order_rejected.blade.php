

<!DOCTYPE html>
<html>
<body style="font-family: Arial; background:#f4f6f8; padding:30px;">

<table width="100%" align="center">
<tr>
<td align="center">

<table width="600" style="background:white; border-radius:10px; overflow:hidden;">

<!-- HEADER -->
<tr>
<td style="background:#dc3545; color:white; padding:20px; text-align:center; font-size:24px; font-weight:bold;">
🌾 AgriConnect Marketplace
</td>
</tr>

<!-- CONTENT -->
<tr>
<td style="padding:30px;">

<div style="background:#fdecea; padding:15px; border-radius:6px; margin-bottom:20px; border:1px solid #f5c6cb;">
<b style="color:#dc3545;">❌ Order Rejected</b>
</div>

<h2>Hello {{ $req->user->name }} 👋</h2>

<p style="font-size:16px;">
We’re sorry to inform you that your order request has been 
<span style="color:#dc3545; font-weight:bold;">REJECTED</span>.
</p>

<p style="color:#777;">
Order ID: #{{ $req->id }}
</p>

<br>

<table width="100%" style="border-collapse:collapse; font-size:16px;">

<tr>
<td style="padding:12px; border-bottom:1px solid #ddd;"><b>Product</b></td>
<td style="padding:12px; border-bottom:1px solid #ddd;">{{ $req->product->product_name }}</td>
</tr>

<tr>
<td style="padding:12px;"><b>Quantity</b></td>
<td style="padding:12px;">{{ $req->quantity }}</td>
</tr>

</table>

<br>

<p>
You may explore other available products on AgriConnect.
</p>

<br>

<div style="text-align:center;">
<a href="{{ url('/products') }}"
style="
background:#dc3545;
color:white;
padding:12px 28px;
text-decoration:none;
border-radius:6px;
font-weight:bold;
">
Browse Products
</a>
</div>

</td>
</tr>

<!-- FOOTER -->
<tr>
<td style="background:#f9fafb; padding:20px; text-align:center; color:#999; font-size:13px;">
Thank you for using <b>AgriConnect</b> 🌱<br>
© {{ date('Y') }} AgriConnect Marketplace
</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>