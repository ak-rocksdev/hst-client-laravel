    </body>
</html>
<footer class="footer navbar-dark bg-dark hidden">
    <div class="container">
        <div class="footer-responsive py-3">
            <div class="copyright footer-responsive-mobile">
                <img src="{{ asset('assets/img/logo_light.png') }}" height="30">
            </div>
            <span id="copyright" class="align-middle text-white footer-responsive-mobile justify-content-center align-self-center montserrat-thin">
                &copy; 2015 - 
                <script>
                    document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
                </script> | Hyper Score Technology
            </span>
            <nav class="d-flex align-middle justify-content-between">
                <ul class="d-flex align-middle justify-content-between mb-0 p-0">
                    <li class="d-flex justify-content-evenly align-self-center">
                        <a href="tel:+6281291720267">
                            <i class="fa-solid fa-phone mx-2 social-link"></i>
                        </a>
                        <a href="https://www.instagram.com/labskate.id" target="_blank">
                            <i class="fab fa-instagram mx-2 social-link"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</footer>
<script src="https://kit.fontawesome.com/8f45faa16b.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/app.js') }}"></script>
@include('layout.script')
@yield('script')