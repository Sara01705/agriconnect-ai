@extends('layouts.app')

@section('content')

<div class="container">

    <h3 class="mb-4">My Buy Requests</h3>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- No Requests --}}
    @if($requests->isEmpty())
        <div class="alert alert-info">
            You have not made any requests yet.
        </div>
    @else

    <table class="table table-bordered table-hover">

        <thead class="table-dark">
            <tr>
                <th width="30%">Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
            @foreach($requests as $request)
            <tr>

                {{-- PRODUCT NAME ONLY --}}
                <td>
                    @if($request->product_name)
                        {{ ucfirst($request->product_name) }}
                    @elseif($request->product)
                        {{ ucfirst($request->product->product_name) }}
                    @else
                        <span class="text-danger">Product Deleted</span>
                    @endif
                </td>

                {{-- QUANTITY --}}
                <td>{{ $request->quantity }}</td>

                {{-- TOTAL PRICE --}}
                <td>₹ {{ number_format($request->total_price, 2) }}</td>

                {{-- STATUS --}}
              <td>
    @if($request->status == 'pending')
    <div class="d-flex align-items-center gap-2" style="min-height: 28px;">
        <span class="badge bg-warning text-dark px-2 py-1">Pending</span>

        <form action="/cancel-request/{{ $request->id }}" method="POST"
              onsubmit="return confirmCancel();">
            @csrf
            <button type="submit" class="badge bg-danger text-white border-0 px-2 py-1">
                Cancel
            </button>
        </form>
    </div>

@elseif($request->status == 'accepted')
    <span class="badge bg-success px-2 py-1">Accepted</span>

@elseif($request->status == 'rejected')
    <span class="badge bg-danger px-2 py-1">Rejected</span>

@elseif($request->status == 'cancelled')
    <span class="badge bg-secondary px-2 py-1">Cancelled</span>
@endif

                {{-- DATE --}}
                <td>{{ $request->created_at->format('d M Y') }}</td>

            </tr>
            @endforeach
        </tbody>

    </table>

    @endif

</div>
<script>
function confirmCancel() {
    return confirm("Are you sure you want to cancel this request?");
}
</script>                                       
@endsection