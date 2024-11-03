@include('partials.head')

<body>

  @include('partials.header')
  <!-- ======= Sidebar ======= -->
  @include('partials.sidebar')
  <main id="main" class="main">
    <div class="row">
      <div class="col">
      <div class="pagetitle">
        <h1>@yield('pagetitle')</h1>
        <nav>
          
        </nav>
      </div><!-- End Page Title -->
    </div>
    @yield('button')
  </div>
</div>
    <section class="section dashboard">
        @yield('content')
    </section>
    <!-- @include('partials.bottom') -->
  </main><!-- End #main -->

    @include('partials.footer')
 @include('partials.script')
</body>
</html>