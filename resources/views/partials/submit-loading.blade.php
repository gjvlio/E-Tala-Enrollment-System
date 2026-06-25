{{-- Global double-submit guard: disables the submit button and shows a spinner
     once a form is validly submitted, preventing duplicate clicks (duplicate
     registrations, double-sent OTPs, etc.). Opt out per form with data-no-loading. --}}
<script>
    (function () {
        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (form.hasAttribute('data-no-loading')) return;

            var btn = e.submitter || form.querySelector('[type="submit"]');
            if (!btn || btn.dataset.loading === '1') return;

            if (btn.hasAttribute('formnovalidate') || btn.value === 'back') return;

            btn.dataset.loading = '1';

            var spinner = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>';
            btn.innerHTML = spinner + (btn.getAttribute('data-loading-text') || 'Please wait…');

            setTimeout(function () { btn.disabled = true; }, 0);
        }, true);
    })();
</script>
