 <!-- Vendor JS Files -->
 <script src="{{ url('assets/js/code.jquery.com_jquery-3.7.1.min.js') }}"></script>
 
 <script src="{{ url('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
 <script src="{{ url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <script src="{{ url('assets/vendor/chart.js/chart.umd.js') }}"></script>
 <script src="{{ url('assets/vendor/echarts/echarts.min.js') }}"></script>
 <script src="{{ url('assets/vendor/quill/quill.min.js') }}"></script>
 <script src="{{ url('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
 <script src="{{ url('assets/vendor/tinymce/tinymce.min.js') }}"></script>
 <script src="{{ url('assets/vendor/php-email-form/validate.js') }}"></script>
 <script src="{{url('assets/js/bootstrap-select.min.js')}}"></script>

<script src="{{url('assets/js/market.js')}}"></script>
 <script src="https://code.jquery.com/jquery-3.7.0.min.js"
     integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
 <!-- Template Main JS File -->
 <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

 <script src="{{ url('assets/js/main.js') }}"></script>
 <script>
    $(".page").change(function(){
        var url = location.href;
        var key = "page_size";
        var value = $(".page").val();
        var patt = new RegExp(key+"=[a-zA-Z0-9]+");
        var matches = patt.exec(url);

        if (matches !== null) {
            var id2 = matches[0];
            url = url.replace(id2, key+'='+value);
        } else {
            url += (url.indexOf('?') !== -1 ? '&' : '?') + key + '=' + value;
        }

        window.location.href = url;
    });
    $(function() {
        $('select').selectpicker("render");
        });
</script>

 @yield('scripts')
