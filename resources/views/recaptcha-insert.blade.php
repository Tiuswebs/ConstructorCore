@if(strlen($component->team->captcha_web_key) > 0)
	@push('header')
		<style type="text/css">
			.grecaptcha-badge {
				left: 0;
				right: auto;
			}
		</style>
	@endpush

	@push('scripts-footer')
		<script src="https://www.google.com/recaptcha/api.js"></script>
		<script>
			function onSubmit(token) {
				document.getElementById("submitButton").click();
			}
		</script>
	@endpush
@endif