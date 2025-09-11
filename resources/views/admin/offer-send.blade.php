@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5 p-3" style="padding: 15px">
        <h2 class="mb-4">🎉 অফার ইমেইল পাঠান</h2>

        <form id="offerForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Discount (%)</label>
                <input type="number" class="form-control" name="discount" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Coupon Code</label>
                <input type="text" class="form-control" name="coupon" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Expiry Date</label>
                <input type="date" class="form-control" name="expiryDate" required>
            </div>
            <button type="submit" class="btn btn-primary">Send Offer Mail</button>
        </form>

        <div id="progressBox" class="mt-4" style="display:none;">
            <h5>📨 Sending Status</h5>
            <div class="progress mb-2">
                <div id="progressBar" class="progress-bar bg-success" style="width:0%">0%</div>
            </div>
            <p><b>Total:</b> <span id="total">0</span> |
                <b>Success:</b> <span id="success">0</span> |
                <b>Failed:</b> <span id="failed">0</span></p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('offerForm').addEventListener('submit', function(e){
            e.preventDefault();

            let formData = new FormData(this);

            document.getElementById('progressBox').style.display = 'block';

            fetch("{{ route('admin.offer.send') }}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('total').innerText = data.total;
                    document.getElementById('success').innerText = data.success;
                    document.getElementById('failed').innerText = data.failed;

                    let percent = (data.success / data.total) * 100;
                    document.getElementById('progressBar').style.width = percent + "%";
                    document.getElementById('progressBar').innerText = Math.round(percent) + "%";
                })
                .catch(err => {
                    alert("Error: " + err);
                });
        });
    </script>
@endsection
