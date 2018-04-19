<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fubbi</title>

    <!-- Bootstrap core CSS -->
    <link href="/landing/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animation CSS -->
    <link href="/landing/css/animate.css" rel="stylesheet">
    <link href="/landing/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/landing/css/style.css" rel="stylesheet">
</head>
<body id="page-top" class="landing-page no-skin-config">
<div class="navbar-wrapper">
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a class="page-scroll" href="#page-top">Home</a></li>
                    <li><a class="page-scroll" href="#features">Features</a></li>
                    <li><a class="page-scroll" href="#about">About</a></li>
                    <li><a class="page-scroll" href="#pricing">Pricing</a></li>
                    <li><a class="page-scroll" href="#contact">Contact</a></li>
                    <li><a class="page-scroll" href="{{action('Auth\LoginController@login')}}">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>


<div id="inSlider" class="carousel carousel-fade m-b-lg" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#inSlider" data-slide-to="0" class="active"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <div class="container">
                <div class="carousel-caption">
                    <h1>
                        231 ways to get attention <br/> leads and customers <br/> through your doors <br/> every month
                    </h1>
                </div>
                <div class="carousel-image wow zoomIn">
                    <img src="/landing/img//landing/laptop.png" alt="laptop"/>
                </div>
            </div>
            <!-- Set background for slide in css -->
            <div class="header-back one"></div>

        </div>
    </div>
    <a class="left carousel-control" href="#inSlider" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#inSlider" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div> {{-- carousel --}}

<section id="features" class="container features m-t-lg">
    <div class="row m-t-lg">
        <div class="col-lg-6 features-text wow fadeInLeft">
            <h2 class="text-info">Fubbi</h2>
            <p>At any one time, our team includes writers and former editors of media outlets such as
                <strong>Forbes</strong>,
                <strong>NY Times</strong>, <strong>Wall St Journal</strong> and more! We'll write your content, design
                your social posts and then
                syndicate to your social platforms 231 times per month - FREE!</p>
        </div>
        <div class="col-lg-6 text-right wow fadeInRight">
            <iframe class="h-300 img-responsive pull-right" id="ytplayer" type="text/html" width="640" height="360"
                    src="http://www.youtube.com/embed/L9sanhoNFhs?autoplay=0&origin=http://portal.fubbi.co/"
                    frameborder="0"></iframe>
        </div>
    </div>

</section> {{-- At any one time.... --}}

<section id="results" class="p-h-md gray-section features">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 fadeInLeft">
                <p>
                    Content marketing costs 62% less than traditional marketing and
                    generates about 3 times as many leads
                </p>
                <small class="big-text">Source: DemandMetric</small>
            </div>
            <div class="col-lg-6 text-right fadeInRight">
                <p>
                    47% of buyers viewed 3-5 pieces <br> of content before engaging <br> with a sales rep.
                </p>
                <small class="big-text">Demand Gen Report</small>
            </div>
        </div>
    </div>
</section> {{-- results --}}

<section id="about" class="">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-8 col-lg-offset-2 col-md-offset-1 m-t-lg m-b-lg">
                <div class="separator"></div>
                <h2 class="separator">Dear Friend,</h2>
                <div class="separator"></div>

                <p>
                    I will never forget the day.
                </p>

                <p>
                    I just hopped a plane from our home in Hawaii to meet with the team in Chicago. As I settled into my
                    seat
                    I opened up a sales spreadsheet - we were on track to do $3 million for the month in revenue.
                </p>

                <p>
                    I then continued on to review our planned media buys - there were dozens needing my approval. In
                    total
                    our
                    spend was close to $1 million in ad buys.
                </p>

                <p>
                    Things were moving forward. But, at the same time, they were falling apart. That’s because the
                    company
                    had
                    grown incredibly fast - a year earlier we were only doing $300,000 in sales per month.
                </p>

                <p>
                    That’s a 1000% increase in business.
                </p>

                <p>
                    Not surprisingly, such astounding growth meant things quickly became… crazy. Almost overnight we
                    were
                    dealing with hundreds of support tickets… we outgrew our CRM… servers were crashing… funnels stopped
                    working… inventory management became a nightmare – the list went on and on.
                </p>

                <p>
                    We excelled at generating new customers, but such growth made it almost impossible to nurture our
                    customers.
                    We could never find the time to build our community.
                </p>

                <p>
                    There were holes in our content marketing and our social media efforts.
                </p>

                <p>
                    Unfortunately, I wasn’t able to take my eye off the $1m per month in media buys we were doing. Or
                    the
                    over
                    40 people in our customer support and telemarketing who needed my focus.
                </p>

                <p>
                    If anybody gave me an "out-of-the-box" solution to our content marketing headaches, I would have
                    snapped
                    it
                    up in a heartbeat.

                </p>

                <p>
                    Nobody ever did.
                </p>

                <p>
                    So I decided to create it myself.
                </p>

            </div>
        </div>
        <div class="navy-line text-center"></div>
        <h2 class="text-center">
            <strong>
                Former Editors From Forbes, The NY Times and
                The Wall St Journal
            </strong>
        </h2>
        <div class="row features-text">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="separator"></div>
                <p>
                    Our editorial team is second to none.
                </p>
                <p>
                    At any one time we can have former editors from the New York Times… Wall St Journal… Forbes and
                    numerous
                    others from the world’s most reputable media outlets.
                </p>
                <p>
                    So we’re probably the most qualified team of content writers around.
                </p>
                <p>
                    Currently we even have a Pulitzer prize winner on our team!
                </p>
                <p>
                    <b>
                        For you this means world class, high quality content every single time.
                    </b>
                </p>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <img class="img-responsive" style="max-height:100px;margin:3em"
                     src="https://images.clickfunnel.com/8a/7590c0707311e78444a729ce5dd9d8/WSJ-Logo.jpg" alt="">
                <img class="img-responsive" style="max-height:100px;margin:3em"
                     src="https://images.clickfunnel.com/04/ff19d0362011e7b330e91b9cf4d92b/Forbes_logo.svg.png" alt="">
                <img class="img-responsive" style="max-height:100px;margin:3em"
                     src="https://images.clickfunnel.com/7b/bc6600361f11e78b135955a5d9b039/nytimes-logo.jpg" alt="">
            </div>
        </div>
        <div class="separator"></div>
        <div class="row">
            <div style="height:140px;" class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <img class="img-responsive" style="width:130px;filter:grayscale(100%);"
                     src="https://images.clickfunnel.com/56/993d109a9411e785c48fa2b56386dd/download-_1_.png" alt="">
            </div>
            <div style="height:140px;" class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <img class="img-responsive" style="width:130px;filter:grayscale(100%);"
                     src="https://images.clickfunnel.com/55/ed1e909a9411e7bd5b370e71f64993/download-_4_.png" alt="">
            </div>
            <div style="height:140px;" class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <img class="img-responsive" style="width:130px;filter:grayscale(100%);"
                     src="https://images.clickfunnel.com/58/9226e09a9411e79a1fffc00e901bc6/download-_2_.png" alt="">
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <img class="img-responsive" style="width:130px;filter:grayscale(100%);"
                     src="https://images.clickfunnel.com/56/aa2d009a9411e79a1fffc00e901bc6/fortune-logo-19481951-1280x739.jpg"
                     alt=""></div>
        </div>
        <div class="separator"></div>
        <div class="row">
            <div style="height:140px;" class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <img style="width:130px;filter:grayscale(100%);"
                     src="https://images.clickfunnel.com/58/91d8c09a9411e780c589114580629f/download-_3_.png" alt=""
                     class="img-responsive"></div>
            <div style="height:140px;" class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <img style="width:130px;filter:grayscale(100%);"
                     src="https://images.clickfunnel.com/57/f041909a9411e7bd5b370e71f64993/national-geographic-logo.png"
                     alt="" class="img-responsive"></div>
            <div style="height:140px;" class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <img style="width:130px;filter:grayscale(100%);"
                     src="https://images.clickfunnel.com/58/de24009a9411e785c48fa2b56386dd/68aa3a98f81f5e09a3afb00a73461434.png"
                     alt="" class="img-responsive"></div>
            <div style="height:140px;" class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <img style="width:130px;filter:grayscale(100%);"
                     src="https://images.clickfunnel.com/56/ad88609a9411e785c48fa2b56386dd/success_logo.png" alt=""
                     class="img-responsive"></div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-md-offset-1 col-lg-offset-1">
                <h2 class="text-center">
                    “Thank you for helping make my
                    dream come true”
                </h2>
                <p class="text-right">
                    Rorion Gracie
                    Co-Founder of the Ultimate Fight Championship (UFC)
                    Founder Gracie Jui-Jitsu Academy
                </p>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
                <img class="img-responsive left"
                     src="https://images.clickfunnel.com/89/7c53c0383811e7b0e995e3055449f8/PK9gBCPS.png" alt="">
            </div>
        </div>
    </div>

</section> {{-- about --}}

<section class="p-h-md gray-section features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 fadeInLeft text-center">
                <p class="big-text">
                    Conversion rates are nearly 6x higher for content marketing adopters than non-
                    adopters (2.9% vs
                    0.5%)
                </p>
                <small class="big-text">Source: Aberdeen</small>
            </div>
        </div>
    </div>
</section> {{-- Conversion rates ... --}}

<section id="pricing" class="pricing">
    <div class="container">
        <div class="row m-b-lg">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Pricing</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 wow zoomIn">
                <ul class="pricing-plan list-unstyled">
                    <li class="pricing-title">
                        Basic
                    </li>
                    <li class="pricing-desc">
                        Billed monthly.
                    </li>
                    <li class="pricing-price">
                        <span>$797</span> / month
                    </li>
                    <li>
                        1 x 1,750 word article p/m
                        <br>
                        <small>()</small>
                    </li>
                    <li>
                        Facebook Posting - FREE
                        <br>
                        <small>(10 posts per month)</small>
                    </li>
                    <li>
                        Instagram Posting - FREE
                        <br>
                        <small>(10 posts per month)</small>
                    </li>
                    <li>
                        Twitter Posting - FREE
                        <br>
                        <small>(10 posts per month)</small>
                    </li>
                    <li>
                        Pinterest Posting - FREE
                        <br>
                        <small>(10 posts per month)</small>
                    </li>
                    <li>
                        LinkedIn Updates - FREE
                        <br>
                        <small>(10 posts per month)</small>
                    </li>
                    <li>
                        365 Day Marketing Calendar - YES
                    </li>
                    <li>
                        LinkedIn Articles
                        <br>
                        <small>(not available)</small>
                    </li>
                    <li>
                        Slideshare
                        <br>
                        <small>(not available)</small>
                    </li>
                    <li>
                        Medium
                        <br>
                        <small>(not available)</small>
                    </li>
                    <li>
                        Quora
                        <br>
                        <small>(not available)</small>
                    </li>
                    <li>
                        <a rel="noreferrer noopener" class="btn btn-primary btn-xs"
                           href="https://fubbico.thrivecart.com/fubbi-basic-plan-v2/">Buy Now</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 wow zoomIn">
                <ul class="pricing-plan list-unstyled">
                    <li class="pricing-title">
                        Bronze
                    </li>
                    <li class="pricing-desc">
                        Billed monthly.
                    </li>
                    <li class="pricing-price">
                        <span>$997</span> / month
                    </li>
                    <li>
                        2 x 1,750 word articles <br>
                        <small>(Approx. 3,500 words p/m)</small>
                    </li>
                    <li>
                        Facebook Posting - FREE <br>
                        <small>(10 posts per month)</small>
                    </li>
                    <li>
                        Instagram Posting - FREE <br>
                        <small>(10 posts per month)</small>
                    </li>
                    <li>
                        Twitter Posting - FREE <br>
                        <small>(10 posts per month)</small>
                    </li>
                    <li>
                        Pinterest Posting - FREE <br>
                        <small>(10 posts per month)</small>
                    </li>
                    <li>
                        LinkedIn Updates - FREE <br>
                        <small>(10 posts per month)</small>
                    </li>
                    <li>
                        365 Day Marketing Calendar - YES
                    </li>
                    <li>
                        LinkedIn Articles <br>
                        <small>(1 per month)</small>
                    </li>
                    <li>
                        Slideshare - FREE <br>
                        <small>(not available)</small>
                    </li>
                    <li>
                        Medium - FREE <br>
                        <small>(not available)</small>
                    </li>
                    <li>
                        Quora - FREE <br>
                        <small>(not available)</small>
                    </li>

                    <li class="plan-action">
                        <a rel="noreferrer noopener" class="btn btn-primary btn-xs"
                           href="https://fubbico.thrivecart.com/fubbi-plan-bronze/">Buy Now</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 wow zoomIn">
                <ul class="pricing-plan list-unstyled">
                    <li class="pricing-title">
                        Silver
                    </li>
                    <li class="pricing-desc">
                        Billed monthly.
                    </li>
                    <li class="pricing-price">
                        <span>$1797</span> / month
                    </li>
                    <li>
                        4 x 1250-1500 word articles
                        <br>
                        <small>(Approx. 5500 words p/m)</small>
                    </li>
                    <li>
                        Facebook Posting - FREE
                        <br>
                        <small>(20 posts per month)</small>
                    </li>
                    <li>
                        Instagram Posting - FREE
                        <br>
                        <small>(20 posts per month)</small>
                    </li>
                    <li>
                        Twitter Posting - FREE
                        <br>
                        <small>(20 posts per month)</small>
                    </li>
                    <li>
                        Pinterest Posting - FREE
                        <br>
                        <small>(20 posts per month)</small>
                    </li>
                    <li>
                        LinkedIn Updates - FREE
                        <br>
                        <small>(20 posts per month)</small>
                    </li>
                    <li>365 Day Marketing Calendar - YES</li>
                    <li>
                        LinkedIn Articles
                        <br>
                        <small>(1 per month)</small>
                    </li>
                    <li>
                        Slideshare - FREE
                        <br>
                        <small>(1 posts per month)</small>
                    </li>
                    <li>
                        Medium - FREE
                        <br>
                        <small>(1 posts per month)</small>
                    </li>
                    <li>
                        Quora - FREE
                        <br>
                        <small>(1 posts per month)</small>
                    </li>
                    <li>
                        <a rel="noreferrer noopener" class="btn btn-primary btn-xs"
                           href="https://fubbico.thrivecart.com/fubbi-silver-plan-v2/">Buy Now</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 wow zoomIn">
                <ul class="pricing-plan list-unstyled">
                    <li class="pricing-title">
                        Gold
                    </li>
                    <li class="pricing-desc">
                        Billed monthly.
                    </li>
                    <li class="pricing-price">
                        <span>$1997</span> / month
                    </li>
                    <li>
                        4 x 1750 word article <br>
                        <small>(Approx. 7000 words p/m)</small>
                    </li>
                    <li>
                        Facebook Posting - FREE <br>
                        <small>(30 posts per month)</small>
                    </li>
                    <li>
                        Instagram Posting - FREE <br>
                        <small>(30 posts per month)</small>
                    </li>
                    <li>
                        Twitter Posting - FREE <br>
                        <small>(30 posts per month)</small>
                    </li>
                    <li>
                        Pinterest Posting - FREE <br>
                        <small>(30 posts per month)</small>
                    </li>
                    <li>
                        LinkedIn Updates - FREE <br>
                        <small>(30 posts per month)</small>
                    </li>
                    <li>
                        365 Day Marketing Calendar - YES <br>
                    </li>
                    <li>
                        LinkedIn Articles <br>
                        <small>(2 per month)</small>
                    </li>
                    <li>
                        Slideshare - FREE <br>
                        <small>(2 posts per month)</small>
                    </li>
                    <li>
                        Medium - FREE <br>
                        <small>(2 posts per month)</small>
                    </li>
                    <li>
                        Quora - FREE <br>
                        <small>(2 posts per month)</small>
                    </li>
                    <li class="plan-action">
                        <a rel="noreferrer noopener" class="btn btn-primary btn-xs"
                           href="https://fubbico.thrivecart.com/fubbi-gold-plan-v2/">Buy Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</section> {{-- pricings --}}

<section class="gray-section features m-t-lg">

    <div class="container">
        <div class="row">
            <div class="navy-line"></div>
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-8 col-lg-offset-2 col-md-offset-1 m-t-lg m-b-lg">
                <h2 class="text-center">
                    We'll Post To Your Social Platforms Up To 231
                    Times Per Month - FREE
                </h2>
                <div class="separator"></div>
                <div class="row">
                    <p>
                        As a company founder, your focus is on boosting profits, increasing sales and attracting new
                        clients.
                        There are many ways to increase sales, one of the most effective is content marketing.
                    </p>
                    <p>
                        With Fubbi, our world class team of content creators produce your blog content. Then our team of
                        designers transform you content into snazzy social post designs.
                    </p>
                    <p>
                        Once we design your social posts, we then syndicate your content across your social platforms.
                        That's
                        up to 231 ways across 10 platforms every month to get leads and attention.
                    </p>
                </div>
                <div class="row m-t-lg big-text">
                    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 col-md-2 col-lg-offset-2">
                        <div class="row text-center">
                            <i class="fa fa-check pull-left"></i>
                            Facebook
                        </div>
                        <div class="row text-center">
                            <i class="fa fa-check pull-left"></i>
                            Twitter
                        </div>
                        <div class="row text-center">
                            <i class="fa fa-check pull-left"></i>
                            Instagram
                        </div>
                        <div class="row text-center">
                            <i class="fa fa-check pull-left"></i>
                            LinkedIn
                        </div>
                        <div class="row text-center">
                            <i class="fa fa-check pull-left"></i>
                            Pinterest
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 ">
                        <div class="row text-center">
                            <i class="fa fa-check pull-left"></i>
                            Quora
                        </div>
                        <div class="row text-center">
                            <i class="fa fa-check pull-left"></i>
                            Medium
                        </div>
                        <div class="row text-center">
                            <i class="fa fa-check pull-left"></i>
                            Google +
                        </div>
                        <div class="row text-center">
                            <i class="fa fa-check pull-left"></i>
                            Slideshare
                        </div>
                        <div class="row text-center">
                            <i class="fa fa-check pull-left"></i>
                            Blog
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> {{--  We'll Post To Your Social Platforms Up To 231.... --}}

<section class="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h2>Frequently Asked Questions</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="features-text">
                    <h3>Which businesses can you write for?</h3>
                    <p>
                        We can write for any businesses in any industry. Our team of writers and editors have hundreds
                        of
                        years of combined experience.
                    </p>
                </div>
                <div class="separator"></div>
                <div class="features-text">
                    <h3>Do I have final approval? </h3>
                    <p>
                        Yes at every stage you have final approval.
                    </p>
                </div>
                <div class="separator"></div>
                <div class="features-text">
                    <h3>Can I change the deliverables of my plan?</h3>
                    <p>
                        Yes, but on a case by case basis.
                    </p>
                </div>
                <div class="separator"></div>
                <div class="features-text">
                    <h3>What if I'm not happy with my content? </h3>
                    <p>
                        It's really simple. We'll rewrite the content until you're happy. Having said that, rewrites are
                        rare as we tend to get it right first time!
                    </p>
                </div>
                <div class="separator"></div>
                <div class="features-text">
                    <h3>How fast will I get results?</h3>
                    <p>
                        Please refer to
                        <a target="_blank" href="http://fubbi.co/how-long-does-content-marketing-take-to-work/">this
                            video</a>
                        for your answer.
                    </p>
                </div>
                <div class="separator"></div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="features-text">
                    <h3>How much time will I spend approving content and designs?</h3>
                    <p>
                        You'll only need to invest between 60 to 90 minutes per month for approvals. That's an average
                        of
                        three minutes a day over a 30 day month. Not bad :)
                    </p>
                </div>
                <div class="separator"></div>
                <div class="features-text">
                    <h3>Do you get access to my social platform passwords?</h3>
                    <p>
                        We do <strong>NOT</strong> get access to your passwords. Our dashboard seamlessly
                        integrates to your social platforms! It
                        takes minutes to set up. The only exception is Quora. If you don't wish to share your Quora
                        passwords,
                        there's an easy solution - we prepare your content and one of your internal team can post it for
                        you.
                    </p>
                </div>
                <div class="separator"></div>
                <div class="features-text">
                    <h3>Can I cancel any time? </h3>
                    <p>
                        Yes. You can cancel any time.
                    </p>
                </div>
                <div class="separator"></div>
                <div class="features-text">
                    <h3>Can you send upload blog content directly to my website?</h3>
                    <p>
                        Yes, if you have WordPress. If you do not have WordPress we will still prepare all your content
                        and
                        get it ready into a schedule. But then you (or someone on your team) will need to upload it.
                    </p>
                </div>
                <div class="separator"></div>
            </div>
        </div>
    </div>


</section> {{-- questions--}}

<section class="timeline gray-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Our workflow</h1>
            </div>
        </div>
        <div class="row features-block">
            <div class="col-lg-12">
                <div id="vertical-timeline" class="vertical-container light-timeline center-orientation">
                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon navy-bg">
                            <i class="fa fa-file-text"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <p>
                                You fill out an onboarding questionnaire. If you know your business well it will take
                                you 10 minutes.
                            </p>
                            <span class="vertical-date m-t-sm"> Day 0 </span>
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon navy-bg">
                            <i class="fa fa-search"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <p>
                                Our team performs a deep dive on your industry and your audience. Within a couple of
                                days we will come back to you with a list of content ideas to approve. Your initial
                                onboarding call takes place at this time, too.
                            </p>
                            <span class="vertical-date m-t-sm"> Days 1-3 </span>
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon navy-bg">
                            <i class="fa fa-tasks"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <p>
                                Once you've approved the content ideas, we then prepare article headlines. You'll
                                usually get between 30 to 50 specific headlines for your content.
                            </p>
                            <span class="vertical-date m-t-sm"> Days 3-7 </span>
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon navy-bg">
                            <i class="fa fa-pencil"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <p>
                                Once you approve the article headlines, our team starts writing your content.
                            </p>
                            <span class="vertical-date m-t-sm"> Day 7-12 </span>
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon navy-bg">
                            <i class="fa fa-desktop"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <p>
                                After you approve the articles, our design team whip up your social posts... they do so
                                in line with your Branding Guidelines.
                            </p>
                            <span class="vertical-date m-t-sm"> Days 12-18 </span>
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon navy-bg">
                            <i class="fa fa-calendar"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <p>
                                We plan out your content schedule over the next 30 days.
                            </p>
                            <span class="vertical-date m-t-sm"> Days 18-24 </span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section> {{-- timeline --}}

<section id="contact" class="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 text-center m-t-lg m-b-lg">
                <p><strong>&copy; 2018 Fubbi</strong><br/>
                    ALL RIGHTS RESERVED<br>
                    Ph: 1300 886 099
                </p>
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <a href="http://fubbi.co/privacy-policy/"><strong>Privacy Policy</strong></a>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <a href="http://fubbi.co/website-disclaimer/"><strong>Disclaimer</strong></a>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <a href="http://fubbi.co/terms-conditions/"><strong>Terms and Conditions</strong></a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section> {{-- footer --}}

<!-- Mainly scripts -->
<script src="/landing/js/jquery-3.1.1.min.js"></script>
<script src="/landing/js/bootstrap.min.js"></script>
<script src="/landing/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/landing/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/landing/js/inspinia.js"></script>
<script src="/landing/js/plugins/pace/pace.min.js"></script>
<script src="/landing/js/plugins/wow/wow.min.js"></script>


<script>

    $(document).ready(function () {

        $('body').scrollspy({
            target: '.navbar-fixed-top',
            offset: 80
        });

        // Page scrolling feature
        $('a.page-scroll').bind('click', function (event) {
            var link = $(this);
            $('html, body').stop().animate({
                scrollTop: $(link.attr('href')).offset().top - 50
            }, 500);
            event.preventDefault();
            $("#navbar").collapse('hide');
        });
    });

    var cbpAnimatedHeader = (function () {
        var docElem = document.documentElement,
                header = document.querySelector('.navbar-default'),
                didScroll = false,
                changeHeaderOn = 200;

        function init() {
            window.addEventListener('scroll', function (event) {
                if (!didScroll) {
                    didScroll = true;
                    setTimeout(scrollPage, 250);
                }
            }, false);
        }

        function scrollPage() {
            var sy = scrollY();
            if (sy >= changeHeaderOn) {
                $(header).addClass('navbar-scroll')
            }
            else {
                $(header).removeClass('navbar-scroll')
            }
            didScroll = false;
        }

        function scrollY() {
            return window.pageYOffset || docElem.scrollTop;
        }

        init();

    })();

    // Activate WOW.js plugin for animation on scrol
    new WOW().init();

</script>

</body>
</html>
