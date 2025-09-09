@extends('user.master')

@section('title', "Product Review")

@section('content')
    <style>
        /* (তুমি যেটা দিয়েছো সব রাখলাম, শুধু rating এর জন্য একটু css extra দিলাম) */
        .stars span {
            font-size: 2rem;
            cursor: pointer;
            color: #ccc;
            transition: .2s;
        }
        .stars span.selected,
        .stars span.hover {
            color: gold;
        }
    </style>

    <div class="container">
        <div class="header">
            <h1>Product Review</h1>
            <p class="product-subtitle">Share your feedback about this item</p>
        </div>

        <div class="response-box success" style="display:none"></div>
        <div class="response-box error" style="display:none"></div>

        <!-- Product Card -->
        <div class="product-card">
            <div class="product-thumb">
                <img src="/{{$product->image}}" alt="Product">
            </div>
            <div class="product-details">
                <h3>{{$product->name}}</h3>
                <p class="product-subtitle">{{$product->seo_description}}</p>
            </div>
        </div>

        <!-- Rating -->
        <div class="selection-panel" data-step="1">
            <div class="selection-title">Rate this Product</div>
            <div class="stars" id="rating-stars">
                <span data-value="1">★</span>
                <span data-value="2">★</span>
                <span data-value="3">★</span>
                <span data-value="4">★</span>
                <span data-value="5">★</span>
            </div>
            <input type="hidden" id="rating" value="">
        </div>

        <!-- Review -->
        <div class="selection-panel" data-step="2">
            <div class="selection-title">Write Your Review</div>
            <div class="player-id-box">
            <textarea id="review"
                      style="width:100%;padding:12px 16px;border-radius:12px;
                 border:2px solid rgba(255,255,255,.1);
                 background:rgba(255,255,255,.1);
                 color:white;font-size:.9rem;outline:none;
                 min-height:100px;resize:none;"
                      placeholder="Write your experience here..."></textarea>
            </div>
        </div>

        <!-- Submit -->
        <button class="checkout-btn" id="submitReview">Submit Review</button>
    </div>

    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loading">
        <div class="spinner"></div>
        <p>Submitting your review...</p>
    </div>

    <script>
        const stars = document.querySelectorAll('#rating-stars span');
        const ratingInput = document.getElementById('rating');
        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                stars.forEach(s => s.classList.remove('hover'));
                star.classList.add('hover');
            });
            star.addEventListener('click', () => {
                stars.forEach(s => s.classList.remove('selected'));
                star.classList.add('selected');
                ratingInput.value = star.dataset.value;
            });
        });

        // Submit Review
        document.getElementById('submitReview').addEventListener('click', async () => {
            const review = document.getElementById('review').value.trim();
            const rating = ratingInput.value;
            const productId = "{{$product->slug}}";

            const loading = document.getElementById('loading');
            const successBox = document.querySelector('.response-box.success');
            const errorBox = document.querySelector('.response-box.error');

            successBox.style.display = 'none';
            errorBox.style.display = 'none';

            if (!rating) {
                errorBox.textContent = "Please select a rating.";
                errorBox.style.display = "block";
                return;
            }
            if (!review) {
                errorBox.textContent = "Please write a review.";
                errorBox.style.display = "block";
                return;
            }

            loading.style.display = 'flex';

            try {
                const res = await fetch("{{route('review.store')}}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{csrf_token()}}"
                    },
                    body: JSON.stringify({
                        review: review,
                        rating: rating,
                        product_id: productId
                    })
                });
                const data = await res.json();
                loading.style.display = 'none';

                if (data.success) {
                    successBox.textContent = data.message;
                    successBox.style.display = "block";
                    document.getElementById('review').value = "";
                    ratingInput.value = "";
                    stars.forEach(s => s.classList.remove('selected'));
                } else {
                    errorBox.textContent = data.message;
                    errorBox.style.display = "block";
                }
            } catch (err) {
                loading.style.display = 'none';
                errorBox.textContent = "Something went wrong!";
                errorBox.style.display = "block";
            }
        });
    </script>
@endsection
