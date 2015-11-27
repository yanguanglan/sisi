<!DOCTYPE html>
<html lang="en">
  <head>
    @include('include.header')
  </head>
  <body>

    @include('include.masthead')

    <div class="listing">
      @yield('content')
    </div>

    @include('include.footer')

  </body>
</html>