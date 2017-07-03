<script>
base_url = '{{ url("/") }}';
domain_name = '{{ my_domain() }}';
</script>

<!--Main JS-->
<script src="{{ asset('scripts/jquery-2.1.3.min.js') }}"></script>
<script src="{{ asset('scripts/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/animsition/jquery.animsition.js') }}"></script>
<script src="{{ asset('vendor/alertify-js/alertify.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-sweetalert/sweet-alert.js') }}"></script>

<!--Page JS-->
@yield('scripts_plugin')

<!--Custom JS-->
<script src="{{ asset('scripts/helper.js') }}"></script>
<script src="{{ asset('scripts/common.js') }}"></script>
@yield('scripts_common')

@yield('scripts')

<script src="{{ asset('scripts/custom.js') }}"></script>