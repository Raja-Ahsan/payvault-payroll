<script>
    document.addEventListener('DOMContentLoaded', function() {
        const steps = document.querySelectorAll('.steps');
        const nextbtn = document.querySelector('#nextbtn');
        const backbtn = document.querySelector('#backbtn');
        const formHeading = document.querySelector('.form-heading');
        const stepHeadings = [
            "General Information",
            "Federal Tax Information",
            "State Tax Information",
            "Income Categories"
        ];
        let currentStep = 0;

        function updateNextButton() {
            if (currentStep === steps.length - 1) {
                nextbtn.textContent = "Submit";
            } else {
                nextbtn.textContent = "Next";
            }
        }

        if (!steps.length || !nextbtn) {
            return;
        }

        function showStep(index) {
            steps.forEach(function(el, i) {
                el.style.display = i === index ? '' : 'none';
            });

            if (formHeading && stepHeadings[index]) {
                formHeading.textContent = stepHeadings[index];
            }
            updateNextButton();
        }

        function clearErrors(stepEl) {
            stepEl.querySelectorAll('.field-error-msg').forEach(function(n) {
                n.remove();
            });
            stepEl.querySelectorAll('.is-invalid').forEach(function(n) {
                n.classList.remove('is-invalid');
            });
        }

        function showFieldError(field, message) {
            field.classList.add('is-invalid');
            var span = document.createElement('span');
            span.className = 'field-error-msg text-danger d-block small mt-1';
            span.textContent = message;

            if (field.type === 'checkbox') {
                var wrap = field.closest('.form-check') || field.parentElement;
                if (wrap) {
                    wrap.appendChild(span);
                } else {
                    field.insertAdjacentElement('afterend', span);
                }
            } else {
                field.insertAdjacentElement('afterend', span);
            }
        }

        function isEmptyRequired(field) {
            var type = (field.type || '').toLowerCase();

            if (type === 'checkbox') {
                return !field.checked;
            }
            if (type === 'file') {
                return !field.files || field.files.length === 0;
            }

            return !String(field.value || '').trim();
        }

        function validateStep(stepEl) {
            clearErrors(stepEl);
            var valid = true;
            var fields = stepEl.querySelectorAll('input, select, textarea');

            fields.forEach(function(field) {
                if (field.disabled) {
                    return;
                }

                if (!field.hasAttribute('required') && !field.required) {
                    return;
                }

                if (isEmptyRequired(field)) {
                    showFieldError(field, 'This field is required');
                    valid = false;
                    return;
                }

                var minLenAttr = field.getAttribute('minlength');
                if (minLenAttr !== null && minLenAttr !== '') {
                    var minLen = parseInt(minLenAttr, 10);
                    if (!isNaN(minLen) && String(field.value || '').trim().length < minLen) {
                        var minMsg = field.getAttribute('data-minlength-message');
                        showFieldError(field, minMsg || ('Enter at least ' + minLen + ' characters'));
                        valid = false;
                    }
                }
            });

            return valid;
        }

        function syncBackButton() {
            if (!backbtn) {
                return;
            }
            backbtn.style.visibility = currentStep === 0 ? 'hidden' : 'visible';
        }

        showStep(0);
        syncBackButton();

        nextbtn.addEventListener('click', function(e) {
            e.preventDefault();
            var panel = steps[currentStep];
            if (!panel || !validateStep(panel)) {
                return;
            }
            if (currentStep >= steps.length - 1) {
                var form = panel.closest('form');
                if (form) {
                    $(form).trigger('submit');
                }
                return;
            }
            currentStep += 1;
            showStep(currentStep);
            syncBackButton();
        });

        if (backbtn) {
            backbtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentStep === 0) {
                    return;
                }
                clearErrors(steps[currentStep]);
                currentStep -= 1;
                showStep(currentStep);
                syncBackButton();
            });
        }

        
    });
</script>