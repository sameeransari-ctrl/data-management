@if (!empty($id))
    <!-- google analytics code for gtag.js  -->
    <script nonce="{{csp_nonce('script')}}" async src="https://www.googletagmanager.com/gtag/js?id={{$id}}"></script>
    <script nonce="{{csp_nonce('script')}}">
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', "{{$id}}");
    </script>
@endif
