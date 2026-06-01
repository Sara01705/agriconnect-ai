<!DOCTYPE html>
<html>
<head>
    <title>AgriConnect-AI</title>

    <!-- CSRF TOKEN -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon_logo.ico') }}">

    <style>
        body {
            background: linear-gradient(135deg, #e9f5ec, #f4f9ff);
            min-height: 100vh;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity:0; transform:translateY(10px);}
            to { opacity:1; transform:translateY(0);}
        }

        .container-xxl {
            max-width:1500px;
        }

        /* PRODUCT CARD */
        .product-card {
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.2);
        }

        .product-img {
            height: 180px;
            width:100%;
            object-fit:cover;
            border-radius: 12px;
        }

        .price {
            font-size:18px;
            font-weight:bold;
            color:#28a745;
        }

        .form-control:focus,
        .form-select:focus {
            border-color:#28a745;
            box-shadow:0 0 0 0.2rem rgba(40,167,69,0.2);
        }

        /* BUTTON HOVER */
        .btn:hover {
            transform: scale(1.05);
            transition: 0.2s;
        }

        /* CHATBOT */
        #chat-icon {
            position:fixed;
            bottom:20px;
            right:20px;
            background:#28a745;
            color:white;
            width:60px;
            height:60px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:24px;
            cursor:pointer;
        }

        #chat-container {
            position:fixed;
            bottom:90px;
            right:20px;
            width:320px;
            background:white;
            border-radius:10px;
            box-shadow:0 4px 10px rgba(0,0,0,0.3);
            display:none;
            flex-direction:column;
            overflow:hidden;
        }

        #chat-header {
            background:#28a745;
            color:white;
            padding:10px;
            font-weight:bold;
            display:flex;
            justify-content:space-between;
        }

        #chat-messages {
            height:250px;
            overflow-y:auto;
            padding:10px;
        }

        #chat-input {
            display:flex;
            border-top:1px solid #ddd;
        }

        #chat-input input {
            flex:1;
            border:none;
            padding:10px;
        }

        #chat-input button {
            background:#28a745;
            color:white;
            border:none;
            padding:10px;
            cursor:pointer;
        }
        .recommended-card {
    border: 2px solid #28a745;
    box-shadow: 0 0 15px rgba(40,167,69,0.3);
}
    </style>
</head>

<body>

<!-- ✅ NAVBAR -->
<nav class="navbar navbar-expand-lg shadow-sm" style="background: linear-gradient(90deg, #1e7e34, #28a745);">

    <div class="container-fluid px-4">

        <!-- Logo -->
        
<a class="navbar-brand d-flex align-items-center text-white" href="{{ route('products.index') }}">
    
    <div style="
        width:55px;
        height:55px;
        border-radius:50%;
        overflow:hidden;
        background:white;
        display:flex;
        align-items:center;
        justify-content:center;
        border:2px solid rgba(255,255,255,0.6);
        box-shadow:0 2px 8px rgba(0,0,0,0.2);
    " class="me-2">

        <img src="{{ asset('images/navbar_logo.png') }}" 
             alt="Logo"
             style="
                width:100%;
                height:100%;
                object-fit:cover;
                transform:scale(1.3);
             ">
             
    </div>

    <span class="fw-bold fs-5">AgriConnect-AI</span>

</a>
        <!-- Right Side -->
        <div class="ms-auto d-flex align-items-center gap-2">

            @if(session('farmer_id'))

                <span class="text-white">
                    Welcome, {{ session('farmer_name') }}
                </span>

                <a href="{{ route('farmer.dashboard') }}" class="btn btn-light btn-sm">
                    Dashboard
                </a>

                <a href="{{ route('buy.requests.index') }}" class="btn btn-warning btn-sm">
                    Buy Requests
                </a>

                <a href="{{ route('farmer.logout') }}" class="btn btn-danger btn-sm">
                    Logout
                </a>

            @elseif(session('user_id'))

                <span class="text-white">
                    Welcome, {{ session('user_name') }}
                </span>

                <a href="{{ route('user.requests') }}" class="btn btn-light btn-sm">
                    My Orders
                </a>

                <a href="{{ route('user.logout') }}" class="btn btn-danger btn-sm">
                    Logout
                </a>

            @elseif(session('admin_id'))

                <span class="text-white">
                    Admin Panel
                </span>

                <a href="{{ route('admin.dashboard') }}" class="btn btn-dark btn-sm">
                    Dashboard
                </a>

                <a href="{{ route('admin.logout') }}" class="btn btn-danger btn-sm">
                    Logout
                </a>

            @else

                <a href="{{ route('price.prediction') }}" class="btn btn-info btn-sm text-white fw-bold">
                    <i class="bi bi-graph-up-arrow"></i> AI Predictor
                </a>

                <a href="{{ route('user.login') }}" class="btn btn-light btn-sm">
                    User Login
                </a>

                <a href="{{ route('farmer.login') }}" class="btn btn-warning btn-sm">
                    Farmer Login
                </a>

                <a href="{{ route('admin.login') }}" class="btn btn-dark btn-sm">
                    Admin Login
                </a>

            @endif

        </div>

    </div>

</nav>

<!-- ✅ MAIN CONTENT -->
<div class="container-xxl mt-4">
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>