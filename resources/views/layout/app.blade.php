<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <base href="{{config("app.url")}}">



    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin />
    <link rel="preconnect" href="https://img.icons8.com" crossorigin />
    <link rel="preconnect" href="https://imageserver.eveonline.com" crossorigin />
    <link rel="preconnect" href="https://images.evetech.net" crossorigin />
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin />

    <link rel="stylesheet" media="print" onload="this.media='all'"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.standalone.min.css"
          integrity="sha256-BqW0zYSKgIYEpELUf5irBCGGR7wQd5VZ/N6OaBEsz5U=" crossorigin="anonymous"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"  media="print" onload="this.media='all'"/>

{{--    <link rel="stylesheet"--}}
{{--          href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"--}}
{{--          integrity="sha256-nbyata2PJRjImhByQzik2ot6gSHSU4Cqdz5bNYL2zcU=" crossorigin="anonymous"/>--}}
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-html5-1.5.1/datatables.min.css"  media="print" onload="this.media='all'"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"  media="print" onload="this.media='all'">
    @if(App\Http\Controllers\ThemeController::isDarkTheme())
        <link rel="stylesheet" href="css/app-dark.css" />
    @else
        <link rel="stylesheet" href="css/app.css"/>
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/bootstrap4-select2-theme@1.0.3/src/css/bootstrap4-select2-theme.css"  media="print" onload="this.media='all'">
    @endif
{{--    <link rel="stylesheet" href="{{asset("css/jquery.inputpicker.css")}}">--}}
    <title>@yield('browser-title', config('app.name')) | {{ config('app.name') }}</title>
    <style>

        @-webkit-keyframes rotating /* Safari and Chrome */
        {
            from {
                -webkit-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            to {
                -webkit-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes rotating {
            from {
                -ms-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -webkit-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            to {
                -ms-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -webkit-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @font-face {
            font-family: 'Shentox 13';
            src: url('fonts/Shentox-Regular2.woff2') format('woff2'),
            url('fonts/Shentox-Regular2.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }
        @keyframes slideIn {
            0% {
                transform: translateY(-1rem) scale(0.9);
                opacity: 0;
            }
            100% {
                transform:translateY(0rem) scale(1);
                opacity: 1;
            }
            0% {
                transform: translateY(-1rem) scale(0.9);
                opacity: 0;
            }
        }
    </style>
    @yield('styles')
    @if (isset($og))
        {!! $og->renderTags() !!}
    @endif
</head>
<body>
@component("layout.navbar")@endcomponent
<div class="container">
    @if(\Illuminate\Support\Facades\Cache::has("recalc-notice"))
        <div class="alert alert-warning border-0 shadow-sm mt-3">
            {{\Illuminate\Support\Facades\Cache::get("recalc-notice")}} <br>
            We are currently processing task #{{\Illuminate\Support\Facades\Cache::get("recalc-current")}} out
            of {{\Illuminate\Support\Facades\Cache::get("recalc-all")}}
        </div>
    @endif
    @yield('content')
</div>
<!-- Footer -->

<footer class="mt-5">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-about">
                    <img class="logo-footer" src="icon.png" style="width: 96px; height: 96px;" alt="logo-footer">
                    <img src="partner.png" style="width: 152px; height: 96px;" alt="logo-footer">
                    <p class="text-justify">
                        The Abyss Tracker is a "killboard" for your Abyssal Deadspace runs - you can save your exact loot saved, aggregated and compared with others.
                        <br>
                        <span class="d-inline-block mt-2 pl-0 ml-0">This is currently version {{config('tracker.version')}}.</span>
                    </p>
                    <p><img src="https://img.icons8.com/small/24/ffffff/github.png" class="tinyicon bringupper mr-1">
                        <a href="https://github.com/molbal/abyss-tracker" class="text-white" target="_blank">
                            Open source
                        </a>
                    </p>
                </div>
                <div class="col-md-4 footer-contact">
                    <h5 class="font-weight-bold text-white">Themes</h5>
                    <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/light.png"/><a class="text-white" href="{{route("customize_set_dark", 0)}}">Light theme</a></p>
                    <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/partly-cloudy-night.png"/><a class="text-white" href="{{route("customize_set_dark", true)}}">Dark theme</a></p>

                    <h5 class="font-weight-bold text-white mt-3">Supporters &amp; Contributors</h5>
                    <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/charity.png"/><a class="text-white" href="{{route("donors.index")}}">Patreon and ISK donors</a></p>
                    <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/icons8-new-logo.png"/><a class="text-white" href="https://icons8.com" target="_blank" rel="nofollow">Icons8</a></p>
                    <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/settings-3.png"/><a class="text-white" href="https://eveworkbench.com" target="_blank" rel="nofollow">EVE Workbench</a></p>
                    <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/settings-3.png"/><a class="text-white" href="https://market.fuzzwork.co.uk/" target="_blank" rel="nofollow">Fuzzwork Market Data</a></p>
                    <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/user.png"/><a class="text-white" href="https://twitter.com/manicvelocity" target="_blank" rel="nofollow">Manic Velocity</a></p>
                    <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/idea.png"/><a class="text-white" href="https://www.jetbrains.com/community/opensource/" target="_blank" rel="nofollow">JetBrains (IDE license)</a></p>
                </div>
                <div class="col-md-4 footer-links">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold text-white">Runs</h5>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/database.png"/><a class="text-white" href="{{route("runs")}}">All runs</a></p>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/search.png"/><a class="text-white" href="{{route("search.index")}}">Search runs</a></p>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/empty-box.png"/><a class="text-white" href="{{route("item_all")}}">Loot table</a></p>
                            <h5 class="font-weight-bold text-white mt-3">Fits</h5>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/job.png"/><a class="text-white" href="{{route("fit.index")}}">Fits</a></p>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/rocket.png"/><a class="text-white" href="{{route('ships_all')}}">Ships</a></p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold text-white">Info pages</h5>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/info.png"/><a class="text-white" href="{{route("infopage.tier", ['tier' => 0])}}">Tranquil <small class="bringupper">(Tier 0)</small></a></p>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/info.png"/><a class="text-white" href="{{route("infopage.tier", ['tier' => 1])}}">Calm <small class="bringupper">(Tier 1)</small></a></p>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/info.png"/><a class="text-white" href="{{route("infopage.tier", ['tier' => 2])}}">Agitated <small class="bringupper">(Tier 2)</small></a></p>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/info.png"/><a class="text-white" href="{{route("infopage.tier", ['tier' => 3])}}">Fierce <small class="bringupper">(Tier 3)</small></a></p>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/info.png"/><a class="text-white" href="{{route("infopage.tier", ['tier' => 4])}}">Raging <small class="bringupper">(Tier 4)</small></a></p>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/info.png"/><a class="text-white" href="{{route("infopage.tier", ['tier' => 5])}}">Chaotic <small class="bringupper">(Tier 5)</small></a></p>
                            <p class="mb-1"><img class="tinyicon bringupper mr-1" src="https://img.icons8.com/small/24/ffffff/info.png"/><a class="text-white" href="{{route("infopage.tier", ['tier' => 6])}}">Cataclysmic <small class="bringupper">(Tier 6)</small></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12 footer-copyright">
                    <p class="text-justify text-small py-1 my-1">
                        Material related to EVE-Online is used with limited permission of CCP Games hf by using official Toolkit. No official affiliation or endorsement by CCP Games hf is stated or implied.
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js"--}}
{{--        integrity="sha256-oNtUmAZAsXy3Pg53xwfYE1YYgfdktOImDPd57g6Ldek=" crossorigin="anonymous"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
        integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/4.8.0/echarts-en.min.js"
        charset="utf-8"></script>
<script src="https://abyss.eve-nt.uk/js/echart.theme.dark.js"
        charset="utf-8"></script>
<script src="https://abyss.eve-nt.uk/js/echart.theme.light.js"
        charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.full.min.js"
        integrity="sha256-vucLmrjdfi9YwjGY/3CQ7HnccFSS/XRS1M/3k/FDXJw=" crossorigin="anonymous"></script>
<script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-html5-1.5.1/datatables.min.js"
></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"
        type="text/javascript" ></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"
></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" defer--}}
{{--></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"--}}
{{--></script>--}}
<script src="{{asset("js/jquery.inputpicker.js")}}"
></script>
<script src="https://cdn.jsdelivr.net/npm/maximize-select2-height@1.0.4/maximize-select2-height.min.js"
        integrity="sha256-rOpd4voNU/iOOklhdb2rhwe4OaXfo7vIO3f7Tc8xe0o=" crossorigin="anonymous"></script>
<script src="{{asset("js/app.js")}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
@yield('scripts')
<script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
@if(!config("app.debug"))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-86961430-8"></script>
    <script>
        // jQuery code
        // Prevent closing from click inside dropdown
        $(document).on('click', '.dropdown-menu', function (e) {
            e.stopPropagation();
        });
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-86961430-8');
    </script>

@endif
</body>
</html>
