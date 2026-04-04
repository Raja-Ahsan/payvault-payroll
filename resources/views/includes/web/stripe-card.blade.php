<div class="field-wrapper mb-[20px]">
    <label class="style-inter mb-[10px] block text-[14px] font-medium text-[var(--text-color)]">Card Details</label>
    <div id="card-element"
        class="min-h-[50px] w-full rounded-[10px] border-[1px] border-[#c5ced9] bg-white px-[14px] py-[12px] transition-[border-color,box-shadow] duration-200 focus-within:border-[var(--primary-color)] focus-within:ring-[2px] focus-within:ring-[var(--primary-color)] focus-within:ring-opacity-[25]">
    </div>
    <div id="card-errors" class="style-inter mt-[10px] text-[14px] text-red-600"></div>
</div>

@push('scripts')
<script>
    var stripe = Stripe("{{ config('services.stripe.public') }}");
    var elements = stripe.elements();

    var card = elements.create("card", {
        style: {
            base: {
                color: "#fff",
                fontSize: "16px",
                "::placeholder": {
                    color: "#ccc"
                }
            }
        }
    });

    card.mount("#card-element");
</script>
@endpush