<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from colorlib.com/etc/lf/Login_v1/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 26 Jun 2023 06:11:05 GMT -->

<head>
    <title>Login V1</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('login/images/icons/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('login/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('login/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('login/vendor/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('login/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('login/vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('login/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('login/css/main.css') }}">
    <meta name="robots" content="noindex, follow">
    <script nonce="c89c127e-0e2e-48cd-a2d0-acdcb3dac805">
        (function(w, d) {
            ! function(dK, dL, dM, dN) {
                dK[dM] = dK[dM] || {};
                dK[dM].executed = [];
                dK.zaraz = {
                    deferred: [],
                    listeners: []
                };
                dK.zaraz.q = [];
                dK.zaraz._f = function(dO) {
                    return function() {
                        var dP = Array.prototype.slice.call(arguments);
                        dK.zaraz.q.push({
                            m: dO,
                            a: dP
                        })
                    }
                };
                for (const dQ of ["track", "set", "debug"]) dK.zaraz[dQ] = dK.zaraz._f(dQ);
                dK.zaraz.init = () => {
                    var dR = dL.getElementsByTagName(dN)[0],
                        dS = dL.createElement(dN),
                        dT = dL.getElementsByTagName("title")[0];
                    dT && (dK[dM].t = dL.getElementsByTagName("title")[0].text);
                    dK[dM].x = Math.random();
                    dK[dM].w = dK.screen.width;
                    dK[dM].h = dK.screen.height;
                    dK[dM].j = dK.innerHeight;
                    dK[dM].e = dK.innerWidth;
                    dK[dM].l = dK.location.href;
                    dK[dM].r = dL.referrer;
                    dK[dM].k = dK.screen.colorDepth;
                    dK[dM].n = dL.characterSet;
                    dK[dM].o = (new Date).getTimezoneOffset();
                    if (dK.dataLayer)
                        for (const dX of Object.entries(Object.entries(dataLayer).reduce(((dY, dZ) => ({
                                ...dY[1],
                                ...dZ[1]
                            })), {}))) zaraz.set(dX[0], dX[1], {
                            scope: "page"
                        });
                    dK[dM].q = [];
                    for (; dK.zaraz.q.length;) {
                        const d_ = dK.zaraz.q.shift();
                        dK[dM].q.push(d_)
                    }
                    dS.defer = !0;
                    for (const ea of [localStorage, sessionStorage]) Object.keys(ea || {}).filter((ec => ec
                        .startsWith("_zaraz_"))).forEach((eb => {
                        try {
                            dK[dM]["z_" + eb.slice(7)] = JSON.parse(ea.getItem(eb))
                        } catch {
                            dK[dM]["z_" + eb.slice(7)] = ea.getItem(eb)
                        }
                    }));
                    dS.referrerPolicy = "origin";
                    dS.src = "../../../cdn-cgi/zaraz/sd0d9.js?z=" + btoa(encodeURIComponent(JSON.stringify(dK[
                        dM])));
                    dR.parentNode.insertBefore(dS, dR)
                };
                ["complete", "interactive"].includes(dL.readyState) ? zaraz.init() : dK.addEventListener(
                    "DOMContentLoaded", zaraz.init)
            }(w, d, "zarazData", "script");
        })(window, document);
    </script>
</head>

<body>
    <div class="limiter">
        @include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{ asset($company->logo) }}" alt="IMG">
                </div>
                <form class="login100-form validate-form" action="{{ route('admin.auth.storeLogin') }}" method="post">
                    @csrf
                    <span class="login100-form-title">
                        Admin Login
                    </span>
                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" type="text" name="email" placeholder="Email" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="password" placeholder="Password" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            Login
                        </button>
                    </div>
                    <div class="text-center p-t-12">
                        <a class="txt2" href="{{ route('admin.auth.forgotPassword') }}">
                            Forgot password?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('login/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('login/vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('login/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('login/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('login/vendor/tilt/tilt.jquery.min.js') }}"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>
    <script src="{{ asset('login/js/main.js') }}"></script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v52afc6f149f6479b8c77fa569edb01181681764108816"
        integrity="sha512-jGCTpDpBAYDGNYR5ztKt4BQPGef1P0giN6ZGVUi835kFF88FOmmn8jBQWNgrNd8g/Yu421NdgWhwQoaOPFflDw=="
        data-cf-beacon='{"rayId":"7dd34a72ba39e9a7","version":"2023.4.0","b":1,"token":"cd0b4b3a733644fc843ef0b185f98241","si":100}'
        crossorigin="anonymous"></script>
</body>

</html>
