document.addEventListener("DOMContentLoaded", () => {
    let selectedPackage = null;
    let selectedPayment = null;

    const diamondOptions = document.querySelectorAll(".diamond-option");
    const paymentOptions = document.querySelectorAll(".payment-option");

    const playerIdInput = document.getElementById("playerId");
    const trxIdInput = document.getElementById("trxId");

    const checkoutBtn = document.getElementById("checkoutBtn");

    const orderResponse = document.getElementById("orderResponse");
    const loadingSpinner = document.getElementById("loadingSpinner");

    // ✅ Step updater
    function updateSteps() {
    const pid = playerIdInput.value.trim();
    document.getElementById("step1").classList.toggle("completed", pid.length >= 6 && pid.length <= 13);
    document.getElementById("step2").classList.toggle("completed", !!selectedPackage);
    document.getElementById("step3").classList.toggle("completed", !!selectedPayment);
}

    // ✅ Response box
    function showResponse(type, message) {
    orderResponse.style.display = "block";
    orderResponse.className = "response-box " + type;
    orderResponse.innerHTML = message;
    loadingSpinner.style.display = "none";
    checkoutBtn.classList.remove("btn-loading"); // reset button
    checkoutBtn.disabled = false;
    window.scrollTo({ top: 0, behavior: "smooth" });
}

    // ✅ Loading spinner (main section)
    function showLoading() {
    loadingSpinner.style.display = "flex";
    orderResponse.style.display = "none";
}

    // ✅ Package selection
    diamondOptions.forEach(el => {
    el.addEventListener("click", () => {
    diamondOptions.forEach(o => o.classList.remove("selected"));
    el.classList.add("selected");
    selectedPackage = parseInt(el.dataset.id, 10);
    document.getElementById("packageError").textContent = "";
    updateSteps();
});
});

    // ✅ Payment method selection
    paymentOptions.forEach(el => {
    el.addEventListener("click", () => {
    paymentOptions.forEach(o => o.classList.remove("selected"));
    el.classList.add("selected");

    selectedPayment = {
    id: parseInt(el.dataset.id, 10),
    method: el.dataset.method,
    number: el.dataset.number,
    description: el.dataset.description
};

    document.getElementById("paymentDetails").innerHTML = `
                    <p><strong>${selectedPayment.method}</strong></p>
                    <p><strong>Number:</strong> ${selectedPayment.number}</p>
                    <br><p>${selectedPayment.description}</p><br>
                `;

    updateSteps();
});
});

    // ✅ Auto-select first payment option
    if (paymentOptions.length > 0) {
    paymentOptions[0].click();
}

    // ✅ Live validation
    playerIdInput.addEventListener("input", updateSteps);

    // ✅ Submit Order
    checkoutBtn.addEventListener("click", () => {
    const pid = playerIdInput.value.trim();
    const trxId = trxIdInput.value.trim();
    let valid = true;

    if (!(pid.length >= 6 && pid.length <= 13)) {
    document.getElementById("playerError").textContent = "Player ID must be 6-13 digits!";
    valid = false;
} else {
    document.getElementById("playerError").textContent = "";
}

    if (!selectedPackage) {
    document.getElementById("packageError").textContent = "অনুগ্রহ করে প্যাকেজ নির্বাচন করুন!";
    valid = false;
}

    if (!selectedPayment) {
    showResponse("error", "❌ অনুগ্রহ করে পেমেন্ট মেথড নির্বাচন করুন!");
    valid = false;
}

    if (trxId.length < 5) {
    document.getElementById("trxError").textContent = "Valid Transaction ID দিন!";
    valid = false;
} else {
    document.getElementById("trxError").textContent = "";
}

    if (!valid) return;

    const orderData = {
    product_id: "{{ $product->id }}",
    item_id: selectedPackage,
    payment_id: selectedPayment.id,
    customer_data: pid,
    transaction_id: trxId,
    _token: "{{ csrf_token() }}"
};

    // ✅ Button loading effect
    checkoutBtn.classList.add("btn-loading");
    checkoutBtn.disabled = true;
    showLoading();

    fetch("{{ route('addOrder') }}", {
    method: "POST",
    headers: {
    "Content-Type": "application/json",
    "X-CSRF-TOKEN": "{{ csrf_token() }}"
},
    body: JSON.stringify(orderData)
})
    .then(res => res.json())
    .then(data => {
    if (data.status) {
    showResponse("success", "✅ অর্ডার সফলভাবে সম্পন্ন হয়েছে!<br>Order ID: " + data.order.id);
    setTimeout(() => window.location.href = "/thank-you", 2000);
} else {
    showResponse("error", "❌ ব্যর্থ: " + data.message);
}
})
    .catch(err => {
    showResponse("error", "⚠️ সার্ভার এরর, আবার চেষ্টা করুন।");
});
});
});
