/**
 * Aczet Unified Real-Time Form Validator
 * Handles onkeyup, oninput, onblur, and onsubmit validation with sleek inline error messages.
 */

document.addEventListener('DOMContentLoaded', function () {
    initAczetValidation();
});

function initAczetValidation() {
    const forms = document.querySelectorAll('form');

    forms.forEach(function (form) {
        // Suppress native browser tooltips
        form.setAttribute('novalidate', 'true');

        const inputs = form.querySelectorAll('input, select, textarea');

        inputs.forEach(function (input) {
            // Real-time validation on keyup, input, and change
            input.addEventListener('keyup', function () {
                validateField(input);
            });
            input.addEventListener('input', function () {
                validateField(input);
            });
            input.addEventListener('blur', function () {
                validateField(input);
            });
        });

        form.addEventListener('submit', function (e) {
            let isFormValid = true;
            inputs.forEach(function (input) {
                const isValid = validateField(input);
                if (!isValid) {
                    isFormValid = false;
                }
            });

            if (!isFormValid) {
                e.preventDefault();
                e.stopPropagation();

                // Focus first invalid field
                const firstInvalid = form.querySelector('.is-invalid-input');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });
    });
}

function validateField(input) {
    // Ignore hidden inputs, CSRF tokens, or submit buttons
    if (input.type === 'hidden' || input.type === 'submit' || input.type === 'button') {
        return true;
    }

    const value = (input.value || '').trim();
    let errorMessage = '';

    // Check if field is required (either by data-required="true", class="required-field", or attribute required)
    const isRequired = input.hasAttribute('required') || input.dataset.required === 'true' || input.classList.contains('required');

    if (isRequired && value === '') {
        const fieldLabel = getFieldLabel(input);
        errorMessage = fieldLabel + ' is required.';
    } else if (value !== '') {
        // Validate Email
        if (input.type === 'email' || input.name === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                errorMessage = 'Please enter a valid email address.';
            }
        }
        // Validate Phone (if entered)
        else if (input.name === 'phone' || input.type === 'tel') {
            const phoneRegex = /^[+0-9\s\-()]{7,20}$/;
            if (!phoneRegex.test(value)) {
                errorMessage = 'Please enter a valid phone number (digits and standard symbols only).';
            }
        }
        // Validate Password confirmation
        else if (input.name === 'password_confirmation') {
            const passwordInput = input.form ? input.form.querySelector('input[name="password"]') : null;
            if (passwordInput && value !== passwordInput.value) {
                errorMessage = 'Passwords do not match.';
            }
        }
        // Minimum length for passwords
        else if (input.type === 'password' && input.name === 'password' && value.length < 6) {
            errorMessage = 'Password must be at least 6 characters long.';
        }
    }

    // Render error message or clear
    let errorContainer = input.parentElement.querySelector('.js-field-error');
    if (!errorContainer) {
        errorContainer = document.createElement('div');
        errorContainer.className = 'js-field-error';
        errorContainer.style.color = '#EF4444';
        errorContainer.style.fontSize = '0.82rem';
        errorContainer.style.fontWeight = '600';
        errorContainer.style.marginTop = '5px';
        errorContainer.style.display = 'none';
        input.parentElement.appendChild(errorContainer);
    }

    if (errorMessage !== '') {
        input.classList.add('is-invalid-input');
        input.style.borderColor = '#EF4444';
        input.style.backgroundColor = '#FEF2F2';
        errorContainer.innerText = errorMessage;
        errorContainer.style.display = 'block';
        return false;
    } else {
        input.classList.remove('is-invalid-input');
        input.style.borderColor = '#CBD5E1';
        input.style.backgroundColor = '#FFFFFF';
        errorContainer.innerText = '';
        errorContainer.style.display = 'none';
        return true;
    }
}

function getFieldLabel(input) {
    if (input.dataset.label) {
        return input.dataset.label.trim();
    }
    if (input.labels && input.labels.length > 0) {
        return input.labels[0].innerText.replace('*', '').trim();
    }
    let current = input.parentElement;
    let depth = 0;
    while (current && depth < 3) {
        const label = current.querySelector('label');
        if (label) {
            return label.innerText.replace('*', '').trim();
        }
        current = current.parentElement;
        depth++;
    }
    const name = input.getAttribute('name') || 'This field';
    return name.replace(/_/g, ' ').replace(/\b\w/g, function (l) { return l.toUpperCase(); });
}

/**
 * Aczet GA4 / GTM / Microsoft Clarity Global Event & UTM Tracker (Section 19 of Pre-Requisites SOW)
 */
(function initAnalyticsAndTracking() {
    window.dataLayer = window.dataLayer || [];
    function trackEvent(eventName, eventParams) {
        window.dataLayer.push({
            'event': eventName,
            ...eventParams,
            'timestamp': new Date().toISOString()
        });
        if (typeof gtag === 'function') {
            gtag('event', eventName, eventParams);
        }
    }

    // Capture UTM parameters from URL and store in session storage
    const urlParams = new URLSearchParams(window.location.search);
    ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'].forEach(param => {
        if (urlParams.has(param)) {
            sessionStorage.setItem(param, urlParams.get(param));
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Track PDF brochure and document downloads
        document.querySelectorAll('a[download], a[href$=".pdf"]').forEach(link => {
            link.addEventListener('click', function () {
                const docUrl = this.getAttribute('href');
                trackEvent('brochure_download', {
                    'file_url': docUrl,
                    'file_name': docUrl.split('/').pop()
                });
            });
        });

        // Track WhatsApp & Phone CTA clicks
        document.querySelectorAll('a[href^="https://wa.me"], a[href^="tel:"]').forEach(cta => {
            cta.addEventListener('click', function () {
                trackEvent('cta_contact_click', {
                    'contact_type': this.getAttribute('href').startsWith('tel:') ? 'phone' : 'whatsapp'
                });
            });
        });

        // Track Lead form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                const action = this.getAttribute('action') || '';
                trackEvent('form_submission', {
                    'form_action': action,
                    'page_path': window.location.pathname
                });
            });
        });
    });

    window.trackAczetEvent = trackEvent;
})();
