<style>
    @font-face {
        font-family: 'GraphikXXCondensed';
        src: url('<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/GraphikXXCondensed-Bold.otf') format('truetype');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }

    @font-face {
        font-family: 'Publico Headline Roman';
        src: url('<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/PublicoHeadline-Roman-Web.woff2') format('woff2');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }

    div.admz,
    div.admz-sp {
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    figure {
        max-width: 100%;
        text-align: center;
    }

    iframe {
        margin: auto;
        max-width: 100%;
    }

    .c-picture__title,
    .c-picture__source {
        text-align: left;
    }

    .d-none {
        display: none !important;
    }

    .d-flex,
    .row {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .flex-nowrap {
        flex-wrap: nowrap;
    }

    .flex-fill {
        flex: 1;
    }

    .flex-column {
        flex-direction: column;
    }

    .flex-row {
        flex-direction: row;
    }

    .justify-content-start {
        justify-content: flex-start;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-start {
        align-items: flex-start;
    }

    .align-items-end {
        align-items: flex-end;
    }

    .align-items-stretch {
        align-items: stretch;
    }

    .flex-wrap {
        flex-wrap: wrap;
    }

    .h-100 {
        height: 100%;
    }

    .fields-wrap {
        background-color: rgba(0, 0, 0, 0.025);
    }

    .fields-wrap:nth-child(even) {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .fields-wrap:last-child {
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }

    <?php foreach (range(1, 4) as $i) : ?>.mt-<?php echo $i; ?>,
    .my-<?php echo $i; ?> {
        margin-top: <?php echo $i / 2; ?>rem !important;
    }

    .mb-<?php echo $i; ?>,
    .my-<?php echo $i; ?> {
        margin-bottom: <?php echo $i / 2; ?>rem !important;
    }

    .ml-<?php echo $i; ?>,
    .mx-<?php echo $i; ?> {
        margin-left: <?php echo $i / 2; ?>rem !important;
    }

    .mr-<?php echo $i; ?>,
    .mx-<?php echo $i; ?> {
        margin-right: <?php echo $i / 2; ?>rem !important;
    }

    .p-<?php echo $i; ?> {
        padding: <?php echo $i / 2; ?>rem;
    }

    .pt-<?php echo $i; ?>,
    .py-<?php echo $i; ?> {
        padding-top: <?php echo $i / 2; ?>rem !important;
    }

    .pb-<?php echo $i; ?>,
    .py-<?php echo $i; ?> {
        padding-bottom: <?php echo $i / 2; ?>rem !important;
    }

    .pl-<?php echo $i; ?>,
    .px-<?php echo $i; ?> {
        padding-left: <?php echo $i / 2; ?>rem !important;
    }

    .pr-<?php echo $i; ?>,
    .px-<?php echo $i; ?> {
        padding-right: <?php echo $i / 2; ?>rem !important;
    }

    <?php endforeach; ?>.text-uppercase {
        text-transform: uppercase;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-primary {
        color: #d32531;
    }

    .content-wrap {
        max-width: 75rem;
    }

    .btn {
        font-family: Graphik, sans-serif;
        outline: none;
        padding: .25rem .5rem;
        border: none;
        cursor: pointer;
        text-align: center;
        font-weight: bold;
        transition: .25s all linear;
        border: 2px solid #BAAB8F;
        color: #000;
        border-radius: 0 !important;
        display: inline-block;
        font-family: 'GraphikXXCondensed';
        font-size: 150%;
        letter-spacing: 2px;
        background-color: #fff;
        background-size: 100% 200%;
        background-position: top;
        transition: all 0.25s ease-in-out;
    }

    .btn.soldout {
        background-color: #000;
        color: #BE0202;
        border: 1px solid #9F9F9F;
    }

    .btn:hover {
        background-position: bottom;
        color: #000;
    }

    .btn.btn-tickets {
        padding-left: 3rem;
        padding-right: 3rem;
        padding-top: .5rem;
        padding-bottom: .5rem;
    }

    .alert {
        position: relative;
        padding: 0.5rem 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .form-control {
        width: 100%;
        padding: .75rem;
        font-size: 1rem;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        font-family: Graphik, sans-serif;
    }

    .form-control::placeholder {
        opacity: .5;
        transition: .25s all linear;
    }

    .form-control:focus::placeholder {
        opacity: .25;
    }

    .rotate180 {
        transform: rotate(180deg);
        transition: .25s all linear;
    }

    <?php for ($i = 1; $i <= 12; $i++) { ?>.col-<?php echo $i; ?> {
        flex: 0 0 <?php echo $i * 8.333333; ?>%;
    }

    <?php } ?>@media (min-width: 48rem) {


        .text-md-right {
            text-align: right !important;
        }

        .btn {
            padding: .5rem 1rem;
        }

        .d-md-none {
            display: none !important;
        }

        .d-md-block {
            display: block !important;
        }

        .d-md-flex {
            display: flex !important;
        }

        .flex-md-row {
            flex-direction: row !important;
        }

        .flex-md-column {
            flex-direction: column !important;
        }

        <?php foreach (range(1, 4) as $i) : ?>.m-md-<?php echo $i; ?> {
            margin: <?php echo $i / 2; ?>rem;
        }

        .ml-md-<?php echo $i; ?>,
        .mx-md-<?php echo $i; ?> {
            margin-left: <?php echo $i / 2; ?>rem !important;
        }

        .mr-md-<?php echo $i; ?>,
        .mx-md-<?php echo $i; ?> {
            margin-right: <?php echo $i / 2; ?>rem !important;
        }

        .mt-md-<?php echo $i; ?>,
        .my-md-<?php echo $i; ?> {
            margin-top: <?php echo $i / 2; ?>rem !important;
        }

        .mb-md-<?php echo $i; ?>,
        .my-md-<?php echo $i; ?> {
            margin-bottom: <?php echo $i / 2; ?>rem !important;
        }

        .p-md-<?php echo $i; ?> {
            padding: <?php echo $i / 2; ?>rem;
        }

        .pt-md-<?php echo $i; ?>,
        .py-md-<?php echo $i; ?> {
            padding-top: <?php echo $i / 2; ?>rem !important;
        }

        .pb-md-<?php echo $i; ?>,
        .py-md-<?php echo $i; ?> {
            padding-bottom: <?php echo $i / 2; ?>rem !important;
        }

        .pl-md-<?php echo $i; ?>,
        .px-md-<?php echo $i; ?> {
            padding-left: <?php echo $i / 2; ?>rem !important;
        }

        .pr-md-<?php echo $i; ?>,
        .px-md-<?php echo $i; ?> {
            padding-right: <?php echo $i / 2; ?>rem !important;
        }

        <?php endforeach; ?><?php for ($i = 1; $i <= 12; $i++) { ?>.col-md-<?php echo $i; ?> {
            flex: 0 0 <?php echo $i * 8.333333; ?>%;
        }

        <?php } ?>
    }

    @media (min-width: 48rem) {
        <?php for ($i = 1; $i <= 12; $i++) { ?>.col-lg-<?php echo $i; ?> {
            flex: 0 0 <?php echo $i * 8.333333; ?>%;
        }

        <?php } ?>
    }


    <?php
    if (is_user_logged_in()) :
    ?>.ui-datepicker-today .ui-state-highlight {
        background: none !important;
        border-color: #444 !important;
        color: inherit !important;
    }

    .dark a.active,
    .dark a.active:hover,
    .dark a.active:focus {
        background-color: #000 !important;
        color: #fff !important;
    }

    .btn .fas {
        transition: .25s all linear;
    }

    .btn .fas.active {
        transform: rotate(180deg)
    }

    .textarea-partial {
        max-height: 150px;
        overflow-y: scroll;
        position: relative;
    }

    .counter-wrap {
        counter-reset: section 1;
    }

    .separator {
        position: relative;
    }

    .separator:before {
        counter-increment: section;
        content: counters(section, '');
        position: absolute;
        left: 0;
        top: 0;
    }

    .separator:before,
    .counter {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: .25rem .5rem;
        height: 100%;
        background: #d32531;
        color: #fff;
        border-radius: 4px;
    }

    .separator hr,
    .divider-h hr {
        visibility: hidden;
    }

    .collapse {
        display: none;
    }

    .collapse.show {
        display: block !important;
    }

    .accordion .card-header button,
    .accordion .card-body {
        border: 1px solid rgba(0, 0, 0, 0.25);
    }

    .accordion .card-header button {
        transition: .25s all linear;
    }

    .accordion .card-body {
        border-radius: 1rem;
    }

    .accordion .card-header button.rounded-top {
        margin-left: 2rem;
        border-radius: 1rem 1rem 0 0 !important;
        border-bottom: 1px solid #fff;
        margin-bottom: -1px;
        border-bottom-color: #fff;
    }

    <?php
    endif; // If logged in 
    ?>@media(min-width: 48rem) {
        #timer-awards-noms-open .sep {
            font-size: 500%;
        }

        #timer-awards-noms-open .number {
            width: 7rem;
            font-size: 500%;
        }

        .how-to-nominate {
            max-width: 80%;
            margin: auto;
        }

        .intro-para {
            max-width: 90%;
        }
    }

    .mw-none {
        max-width: none !important;
    }

    #content-wrap,
    .content-wrap {
        position: relative;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }

    .content-wrap {
        width: 100%;
    }

    .l-footer__wrap {
        max-width: 1000px;
    }

    body {
        background-color: #000;
    }

    .bg-semi-dark {
        background-color: rgba(0, 0, 0, .5);
        color: #fff;
    }

    .l-header__wrap,
    .intro,
    .shows {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;

        position: relative;
    }

    .l-header__wrap .content,
    .intro .content {
        z-index: 2;
    }

    /* .l-header__wrap:after,
    .intro:after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, .85) 0%, rgba(0, 0, 0, .25) 100%);
        z-index: 1;
    } */

    .l-header__wrap:after {
        z-index: -1;
    }

    .l-header__wrap {
        background-color: transparent;
        transition: .25s all linear;
        position: relative;
        background-image: url(<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/feature-image.jpg);
        color: #fff;
        text-align: center;
        padding: 1rem 1rem 3rem 1rem;
    }

    .l-header__wrap .l-header__content,
    .intro {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2rem;
    }

    .intro-wrap {
        background-position: top;
        background-size: contain;
        background-repeat: repeat;
        background-image: url(<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/free-black-paper-texture.jpg);
    }

    .intro {
        justify-content: flex-end;
        /* border: 1rem solid #fff; */
        width: 100%;
        margin: auto;
        min-height: 100vh;
        background-image: url(<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last-2/intro.jpg);
    }

    @media (min-height: 1200px) {
        .intro {
            min-height: 600px !important;
        }
    }

    .shows {
        color: #fff;
        padding: 4rem 2rem;
        text-align: center;
        justify-content: flex-end;
        font-family: Graphik, sans-serif;
        /* background-position: top;
        background-size: contain;
        background-repeat: repeat;
        background-image: url(<?php echo get_template_directory_uri(); ?>/images/jd-live-at-last/free-black-paper-texture.jpg); */
    }

    .shows img {
        filter: grayscale(1);
    }

    .shows h2,
    .latest-news h2 {
        font-family: 'GraphikXXCondensed', sans-serif;
        font-weight: bold;
        font-size: 300%;
        line-height: 175%;
        text-transform: uppercase;
    }

    .shows h3 {
        font-weight: bold;
        font-size: 150%;
        margin: 1rem auto .5rem;
    }

    .latest-news {
        background-color: #fff;
    }

    .latest-news h2 {
        border-top: 3px solid #7A592D;
    }

    .latest-news .news h4 {
        font-size: 150%;
        line-height: 135%;
        font-weight: bold;
        margin-bottom: .5rem;
    }

    .latest-news .news {
        display: block;
        background-color: #fff;
        padding: 1rem;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        color: #000;
    }

    .latest-news .news p {
        font-style: italic;
    }

    .previous-events {
        border-top: .5rem solid #baab8f;
        padding: 1.5rem;
        background-color: #000;
    }

    .previous-events h3.heading {
        font-family: Georgia, Times, Times New Roman, serif;
        text-align: center;
        color: #fff;
        font-size: 200%;
        margin: 0 auto 1.5rem auto;
    }

    .previous-events .img-wrap {
        position: relative;
    }

    .previous-events .img-wrap::after {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: rgba(0, 0, 0, .7);
    }

    .l-header__wrap .text,
    .intro .text {
        color: #fff;
        max-width: 100%;
        text-align: center;
        font-weight: bold;
        font-size: 120%;
        line-height: 125%;

        border: 1px solid rgba(255, 255, 255, .5);
        padding: .5rem;
    }

    .intro .text .text-inner {
        padding-bottom: 3rem !important;
    }

    .intro-btn-wrap {
        margin-top: -1.5rem;
    }

    .intro-btn-wrap .btn {
        background-color: #fff;
        color: #000;
        border: none;
    }

    .intro .text h3 {
        margin-bottom: 1rem;
    }

    .l-header__wrap .text .text-inner,
    .intro .text .text-inner {
        background-color: #000;
        padding: 1rem;
        border: 1px solid rgba(255, 255, 255, .25);
        font-family: 'Publico Headline Roman', serif;
        font-weight: normal;
    }

    .header-bottom,
    .gigs-bottom {
        font-family: 'GraphikXXCondensed', sans-serif;
        background-color: #000;
        text-align: center;
        color: #fff;
        padding: 1rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .l-header__wrap.scrolled {
        background: linear-gradient(to bottom, #000, transparent);
        position: relative;
        height: 80px;
    }

    .l-header__wrap .l-header__branding {
        padding: 1rem .5rem;
    }

    .l-header__wrap .l-header__branding a {
        color: #fff;
        font-size: .9rem;
    }

    .l-header__wrap .l-header__branding a span {
        font-size: .85rem;
    }

    .l-header__wrap .l-header__branding img {
        transition: .25s all linear;
    }

    .l-header__wrap.scrolled .l-header__branding {
        padding: .25rem;
    }

    .l-header__wrap.scrolled .l-header__branding img {
        width: 9rem;
        top: 0;
    }

    .l-header__branding img {
        /* filter: brightness(0) invert(1); */
    }

    .ad-bb-header {
        display: none;
    }

    input[type="password"] {
        border: 1px solid;
    }

    #skin-ad-section,
    #onesignal-bell-container,
    #onesignal-slidedown-dialog {
        display: none !important;
    }

    .news-links .col-md-6 {
        padding: .5rem;
    }

    .news-links a {
        display: flex;
    }

    .news-links a .img-wrap {
        width: 120px;
        overflow: hidden;
        flex: 1 0 auto;
        margin-right: 1rem;
        position: relative;
        height: 100px !important;
    }

    .news-links a img {
        height: 100% !important;
        width: auto;
        max-width: none;
        margin-right: 1rem;

        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .news-links a,
    .news-links a:visited {
        color: #df3535;
        text-decoration: none;
        transition: .25s all linear;
    }

    .news-links a:hover {
        color: #000;
    }

    .l-header__nav {
        width: auto !important;
        height: auto !important;
    }

    #content-wrap {
        max-width: none;
    }

    #content-wrap section {
        max-width: 1000px;
        margin: auto;
    }

    .subheading {
        background-color: #fff;
        text-align: center;
        padding: .25rem .5rem;
        font-family: Graphik, sans-serif;
        text-transform: uppercase;
        font-weight: bold;
        letter-spacing: 1px;
        padding-top: 1rem;
        position: relative;
    }

    .subheading:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        background-color: #000;
        height: .5rem;
        border-top: 1px solid #fff;
    }

    .gigs-wrap {
        margin: 2rem auto;
        font-family: Graphik, sans-serif;
        flex-wrap: wrap;
    }



    .gigs .gigs-menu {}

    .gigs .gigs-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .gigs .gigs-menu ul li a {
        display: block;
        color: #fff;
        text-transform: uppercase;
        font-size: 90%;
        font-weight: bold;
    }

    .gigs .gigs-heading {
        color: #fff;
    }

    .gigs .gigs-heading .thick-line {
        height: .35rem;
        padding-bottom: .5rem;
        border-top: .5rem solid #707070;
        border-bottom: 1px solid #707070;
    }

    .gigs .gigs-heading .box {
        border: 1px solid rgba(255, 255, 255, .25);
        padding: 1rem;
    }

    .gigs .gigs-heading h3 {
        font-size: 150%;
        font-weight: bold;
    }

    .gig-item {
        font-family: Graphik, sans-serif;
        text-align: center;
        width: 100%;
        padding-left: 1rem;
        padding-right: 1rem;
        font-size: 90%;
        line-height: 130%;
        margin-top: 1rem;
        margin-bottom: 2rem;
    }

    .gig-item:nth-of-type(3n) {
        border-right: none;
    }


    .gig-item .gig-info {
        padding: 1rem;
        border: 1px solid rgba(0, 0, 0, .5);
        color: #fff;
    }

    .gig-item .gig-info h3 {
        font-size: 150%;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: .5rem;
    }

    .gig-item .time-location {
        color: #fff;
    }

    .gig-item .img-wrap {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .desktop {
        display: none;
    }

    @media (max-width: 40rem) {
        #content-wrap {
            background-position: center;
            background-size: contain;
        }
    }

    @media (max-width: 59.9375rem) {
        .l-header__menu {
            display: flex;
            flex-direction: column;
            width: 100%;
            border: 1px solid #777;
        }

        .l-header__menu:after {
            content: ">";
            transform: rotate(90deg);
            position: absolute;
            top: .55rem;
            right: 1rem;
            color: #d32531;
            font-size: 150%;
            z-index: -1;
        }

        .l-header__menu li {
            display: none;
            width: 100%;
            padding: .5rem 1rem;
            text-align: center;
        }

        .l-header__menu li a {
            display: block;
            width: 100%;
            text-align: center;
            color: #333 !important;
        }

        .l-header__menu li.active,
        .l-header__menu li.show {
            display: inherit;
        }

        .l-header__menu li.active a {
            color: #d32531 !important;
        }

        #content-wrap {}

        .l-header__wrap {
            height: 6.5rem;
        }

        .l-header__wrap.active {
            height: 12rem;
        }

        .l-header__nav {
            position: relative;
        }

        .l-header__block--right {
            top: 1rem !important;
        }
    }

    .l-header__content--sticky {
        overflow-x: hidden;
    }

    .feature-image {
        width: 55%;
    }

    @media (min-width: 60rem) {

        .intro {
            border-width: 3rem;
            width: 75%;
        }

        .l-header__wrap {
            padding: 3rem 1rem 5rem 1rem;
        }

        .l-header__wrap .text {
            width: 55%;
        }

        .intro .text {
            width: 75%;
        }

        .intro .text h3 {
            margin-bottom: 1rem;
        }

        .l-header__wrap .l-header__branding a {
            font-size: 1.5rem;
        }

        .l-header__wrap .l-header__branding a span {
            font-size: 1rem;
        }

        .l-header,
        .l-header__content,
        .l-header__wrap {
            height: auto;
        }

        .l-header__wrap.scrolled .l-header__branding img {
            width: 12rem;
        }

        .gigs-wrap {
            flex-wrap: nowrap;
        }

        .gigs .gigs-heading {
            width: 20%;
        }

        .gigs .gig-items {
            width: 60%;
        }

        .gig-item {
            width: calc(100% / 3);
            margin-top: 0;
            border-right: 1px solid rgba(255, 255, 255, .25);
        }

        .gig-item .img-wrap {
            height: 0;
            padding-bottom: 67%;
        }
    }

    .header-sub-text {
        color: #fff;
        text-align: center;
        font-family: Graphik, sans-serif;
        letter-spacing: .05rem;
    }

    .rsa-header {
        background-color: #000;
    }


    @media (min-width: 60rem) {}

    @media (max-width: 59.9375rem) {


        .rsa-header {
            background-position: center -10vh;
        }

        .l-header__wrap {
            height: auto;
        }

        .rsa-header-left,
        .rsa-header-right {
            display: none;
        }

        .l-header__menu {
            flex-direction: row !important
        }

        .l-header__menu li {
            width: auto !important;
        }
    }

    @media (max-width: 23.4375rem) {}

    @media (min-width: 576px) {
        .mobile {
            display: none;
        }
        .desktop {
            display: block;
        }
	}

    .l-header,
    .l-header__wrap {
        height: auto;
    }

    header {
        top: 0;
        left: 0;
        right: 0;
        z-index: 2;
    }

    .rsa-header-top .rsa-header-top-red {
        background-color: #CD232A;
    }

    .rsa-header-top .rsa-header-top-white {
        background-color: #fff;
        height: .25rem;
        border-bottom: 2px solid #CD232A;
    }

    .l-header__menu li {
        display: inline-block !important;
    }

    .l-header__menu li a,
    .l-header__menu-link {
        color: #fff !important;
    }

    .l-header__menu li.active a,
    .l-header__menu-link:hover {
        color: #000 !important;
    }

    .para-1,
    .para-2,
    .para-3 {
        font-family: Graphik, sans-serif;

        color: #fff;
        text-transform: uppercase;

        text-align: center;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .news-pieces .news-piece {
        margin: 1rem 0;
    }

    .news-pieces a {
        display: block;
        background-color: rgba(255, 255, 255, 0.15);
        height: 100%;
        margin: 1rem;
    }

    .news-pieces a .img-wrap {
        width: 100%;
        height: 0;
        padding-bottom: 52%;
        overflow-y: hidden;
        position: relative;
    }

    .news-pieces a .img-wrap img {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .news-pieces a .link-text {
        margin: 1rem 1rem 0;
        color: #fff !important
    }

    .artist-img-wrap {
        line-height: 0;
    }


    @media (min-width: 48rem) {

        .para-1,
        .para-2,
        .para-3 {
            width: 75%;
            margin: auto;
        }

        .para-2 {
            float: right;
        }

        .para-3 {
            float: left;
            margin-left: 7rem;
        }
    }

    .map iframe{
        pointer-events: none;
    }
</style>