<?php
/**
 * Template Name: Subscribe
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-28
 */
?>
<?php
wp_redirect( 'https://thebrag.com/observer/' ); exit;
use \DrewM\MailChimp\MailChimp;
$success = false;
if ( isset( $_POST ) && count( $_POST ) > 0 ) :
   $form_posts = stripslashes_deep($_POST);
   require_once( get_template_directory() . '/MailChimp.php');
   $api_key = '727643e6b14470301125c15a490425a8-us1';
   $MailChimp = new MailChimp( $api_key );

   foreach( $form_posts['list'] as $ids ) :
     $arr_ids = explode( '-', $ids );
     if ( ! isset( $arr_ids[0] ) )
      continue;

      $list_id = $arr_ids[0];

       $data = array(
           'email_address' => $form_posts['email'],
           'status' => 'subscribed',
       );
       $subscribe = $MailChimp->post( "lists/$list_id/members", $data );

       if ( isset( $arr_ids[1] ) ) :
         $subscriber_hash = MailChimp::subscriberHash( $form_posts['email'] );
         $interest_subscribe = $MailChimp->patch("lists/$list_id/members/$subscriber_hash", [
            'interests'    => [ $arr_ids[1] => true ],
	       ]);
         // echo '<pre>'; print_r( $interest_subscribe ); echo '</pre>';
       endif;

       $success = true;
       // header( 'Location: thank-you-subscribe' );
   endforeach;
endif;
?>
<?php
get_header();
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
<style class="cp-pen-styles">
@import url("https://fonts.googleapis.com/css?family=Istok+Web|Montserrat:400,700");
* {
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
*:before, *:after {
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
}

ul { margin: 0 !important;}

.container {
  padding: 80px 40px 40px 40px;
}

section {
  padding: 25px 0;
}

#navigation {
  position: fixed;
  width: 100%;
  left: 0;
  right: 0;
  top: 10px;
  max-width: 1060px;
  margin: auto auto;
  padding: 0px;
  z-index: 20;
}
#navigation .alert {
  margin-bottom: 0;
  border-radius: 0;
  z-index: 5;
  margin-top: 15px;
}
#navigation .nav {
  z-index: 10;
  position: relative;
  background: #000000;
  box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.25);
}
#navigation .nav a {
  color: #fff;
  padding: 10px;
  font-weight: bold;
  font-size: 13px;
}
#navigation .nav a.save {
  background: #666;
  transition: all 500ms ease-out;
}

h1 {
  font-family: "Montserrat", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-weight: bold;
  font-size: 40px;
  color: #000000;
  letter-spacing: 0;
  margin-bottom: 5px;
  letter-spacing: -1px;
}

h2 {
  font-family: "Istok", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 16px;
  color: #000000;
  letter-spacing: 0;
  line-height: 24px;
  margin-bottom: 30px;
}

.unsubscribe {
  text-align: right;
  padding-top: 10px;
  font-weight: bold;
  font-size: 14px;
}

.row-cards .col-sm-12, .col-sm-6, .col-sm-4 {
  margin-bottom: 30px;
}

.card-content {
  border: 1px solid #dddee4;
  position: relative;
  height: 100%;
  flex-direction: column;
}
.card-content * {
  cursor: pointer;
}
.card-content h4 {
  font-family: "Montserrat", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-weight: bold;
  font-size: 20px;
  color: #000000;
  letter-spacing: -1px;
}
.card-content h4:after {
  width: 30px;
  height: 2px;
  content: '';
  background: #000;
  display: block;
  margin: 10px 0;
}
.card-content p {
  font-family: "Istok", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 15px;
  color: #282828;
  letter-spacing: 0;
  line-height: 23px;
}

.c-card {
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
  visibility: hidden;
}
.c-card ~ .card-content {
  transition: all 800ms ease-out;
}
.c-card ~ .card-content .card-state-icon {
  position: absolute;
  top: 7px;
  right: 5px;
  z-index: 2;
  width: 20px;
  height: 20px;
  background: url(data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHZlcnNpb249IjEuMSIgdmlld0JveD0iMCAwIDI2IDI2IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAyNiAyNiIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+CiAgPHBhdGggZD0ibS4zLDE0Yy0wLjItMC4yLTAuMy0wLjUtMC4zLTAuN3MwLjEtMC41IDAuMy0wLjdsMS40LTEuNGMwLjQtMC40IDEtMC40IDEuNCwwbC4xLC4xIDUuNSw1LjljMC4yLDAuMiAwLjUsMC4yIDAuNywwbDEzLjQtMTMuOWgwLjF2LTguODgxNzhlLTE2YzAuNC0wLjQgMS0wLjQgMS40LDBsMS40LDEuNGMwLjQsMC40IDAuNCwxIDAsMS40bDAsMC0xNiwxNi42Yy0wLjIsMC4yLTAuNCwwLjMtMC43LDAuMy0wLjMsMC0wLjUtMC4xLTAuNy0wLjNsLTcuOC04LjQtLjItLjN6IiBmaWxsPSIjRkZGRkZGIi8+Cjwvc3ZnPgo=) no-repeat;
  transition: all 100ms ease-out;
}
.c-card ~ .card-content:before {
  position: absolute;
  top: 1px;
  right: 1px;
  content: "";
  width: 0;
  height: 0;
  border-top: 52px solid transparent;
  border-left: 52px solid transparent;
  transition: all 200ms ease-out;
}
.c-card ~ .card-content:after {
  position: absolute;
  top: 1px;
  right: 1px;
  content: "";
  width: 0;
  height: 0;
  border-top: 50px solid #fff;
  border-left: 50px solid transparent;
  transition: all 200ms ease-out;
}
.c-card ~ .card-content label {
  margin-bottom: 0;
  height: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.c-card ~ .card-content .card-text {
  padding: 25px;
  background: #fff;
  flex-grow: 1;
  transition: all 200ms ease-out;
}
.c-card ~ .card-content .card-bottom {
  background: #f8f8f8;
  border-top: 1px solid #dddee4;
  padding: 15px 25px;
  transition: all 200ms ease-out;
}
.c-card ~ .card-content .card-bottom span {
  font-family: "Montserrat", "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-weight: bold;
  font-size: 14px;
  color: #000000;
  letter-spacing: 0;
}
.c-card ~ .card-content .card-bottom span:before {
  width: 20px;
  height: 20px;
  display: inline-block;
  vertical-align: middle;
  margin-right: 5px;
  content: '';
  background: url("data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCIgdmlld0JveD0iMCAwIDUxMS42MzQgNTExLjYzNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTExLjYzNCA1MTEuNjM0OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggZD0iTTQ4Mi41MTMsODMuOTQyYy03LjIyNS03LjIzMy0xNS43OTctMTAuODUtMjUuNjk0LTEwLjg1aC0zNi41NDF2LTI3LjQxYzAtMTIuNTYtNC40NzctMjMuMzE1LTEzLjQyMi0zMi4yNjEgICBDMzk3LjkwNiw0LjQ3NSwzODcuMTU3LDAsMzc0LjU5MSwwaC0xOC4yNjhjLTEyLjU2NSwwLTIzLjMxOCw0LjQ3NS0zMi4yNjQsMTMuNDIyYy04Ljk0OSw4Ljk0NS0xMy40MjIsMTkuNzAxLTEzLjQyMiwzMi4yNjF2MjcuNDEgICBoLTEwOS42M3YtMjcuNDFjMC0xMi41Ni00LjQ3NS0yMy4zMTUtMTMuNDIyLTMyLjI2MUMxNzguNjQsNC40NzUsMTY3Ljg4NiwwLDE1NS4zMjEsMEgxMzcuMDUgICBjLTEyLjU2MiwwLTIzLjMxNyw0LjQ3NS0zMi4yNjQsMTMuNDIyYy04Ljk0NSw4Ljk0NS0xMy40MjEsMTkuNzAxLTEzLjQyMSwzMi4yNjF2MjcuNDFINTQuODIzYy05LjksMC0xOC40NjQsMy42MTctMjUuNjk3LDEwLjg1ICAgYy03LjIzMyw3LjIzMi0xMC44NSwxNS44LTEwLjg1LDI1LjY5N3YzNjUuNDUzYzAsOS44OSwzLjYxNywxOC40NTYsMTAuODUsMjUuNjkzYzcuMjMyLDcuMjMxLDE1Ljc5NiwxMC44NDksMjUuNjk3LDEwLjg0OWg0MDEuOTg5ICAgYzkuODk3LDAsMTguNDctMy42MTcsMjUuNjk0LTEwLjg0OWM3LjIzNC03LjIzNCwxMC44NTItMTUuODA0LDEwLjg1Mi0yNS42OTNWMTA5LjYzOSAgIEM0OTMuMzU3LDk5LjczOSw0ODkuNzQzLDkxLjE3NSw0ODIuNTEzLDgzLjk0MnogTTM0Ny4xODcsNDUuNjg2YzAtMi42NjcsMC44NDktNC44NTgsMi41Ni02LjU2NyAgIGMxLjcxMS0xLjcxMSwzLjkwMS0yLjU2OCw2LjU3LTIuNTY4aDE4LjI2OGMyLjY3LDAsNC44NTMsMC44NTQsNi41NywyLjU2OGMxLjcxMiwxLjcxMiwyLjU2NywzLjkwMywyLjU2Nyw2LjU2N3Y4Mi4yMjQgICBjMCwyLjY2Ni0wLjg1NSw0Ljg1My0yLjU2Nyw2LjU2N2MtMS43MTgsMS43MDktMy45LDIuNTY4LTYuNTcsMi41NjhoLTE4LjI2OGMtMi42NjksMC00Ljg1OS0wLjg1NS02LjU3LTIuNTY4ICAgYy0xLjcxMS0xLjcxNS0yLjU2LTMuOTAxLTIuNTYtNi41NjdWNDUuNjg2eiBNMTI3LjkxNSw0NS42ODZjMC0yLjY2NywwLjg1NS00Ljg1OCwyLjU2OC02LjU2NyAgIGMxLjcxNC0xLjcxMSwzLjkwMS0yLjU2OCw2LjU2Ny0yLjU2OGgxOC4yNzFjMi42NjcsMCw0Ljg1OCwwLjg1NCw2LjU2NywyLjU2OGMxLjcxMSwxLjcxMiwyLjU3LDMuOTAzLDIuNTcsNi41Njd2ODIuMjI0ICAgYzAsMi42NjYtMC44NTUsNC44NTYtMi41Nyw2LjU2N2MtMS43MTMsMS43MDktMy45LDIuNTY4LTYuNTY3LDIuNTY4SDEzNy4wNWMtMi42NjYsMC00Ljg1Ni0wLjg1NS02LjU2Ny0yLjU2OCAgIGMtMS43MDktMS43MTUtMi41NjgtMy45MDEtMi41NjgtNi41NjdWNDUuNjg2eiBNNDU2LjgxMiw0NzUuMDg4SDU0LjgyM3YtMjkyLjM2aDQwMS45ODlWNDc1LjA4OHoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K") no-repeat;
}
.c-card ~ .card-content:hover {
  border: 1px solid #d32531;
}
.c-card:checked ~ .card-content {
  border: 1px solid #d32531;
  box-shadow: 0 10px 15px 0 rgba(0, 0, 0, 0.15);
}
.c-card:checked ~ .card-content:before, .c-card:checked ~ .card-content:after {
  border-top: 52px solid #000000;
}
.c-card:checked ~ .card-content .card-text {
  background: #d32531;
}
.c-card:checked ~ .card-content .card-text * {
  color: #fff;
}
.c-card:checked ~ .card-content .card-bottom {
  background: #d32531;
  border-top: 1px solid #000000;
  padding: 15px 25px;
}
.c-card:checked ~ .card-content .card-bottom span {
  color: white;
}
.c-card:checked:hover ~ .card-content:before, .c-card:checked:hover ~ .card-content:after {
  border-top: 52px solid #b21900;
}
.c-card:checked:hover ~ .card-content .card-state-icon {
  background: url("data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCIgdmlld0JveD0iMCAwIDM1NyAzNTciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDM1NyAzNTc7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8ZyBpZD0iY2xvc2UiPgoJCTxwb2x5Z29uIHBvaW50cz0iMzU3LDM1LjcgMzIxLjMsMCAxNzguNSwxNDIuOCAzNS43LDAgMCwzNS43IDE0Mi44LDE3OC41IDAsMzIxLjMgMzUuNywzNTcgMTc4LjUsMjE0LjIgMzIxLjMsMzU3IDM1NywzMjEuMyAgICAgMjE0LjIsMTc4LjUgICAiIGZpbGw9IiNGRkZGRkYiLz4KCTwvZz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K") no-repeat;
  transform: scale(0.9);
}

.footer {
    border-top:1px solid #000;
    padding-top:20px;
    font-size:.9em;
    text-align:center;
}

.checkbox {
    margin-bottom:20px;
    display: inline-block;
}

.checkbox label {
    color:#666;
}

label p {
    margin:0;
}

h4 {
    margin:0;
    color:#000;
}

.logo {
    border-bottom:1px solid #000;
    margin:20px 0 0;
    padding:10px;
    text-align: center;
}
.logo img {
    width:300px;
}

@keyframes jump {
  0% {
    transform: translateY(0);
  }
  40% {
    transform: translateY(-5px);
  }
  100% {
    transform: translateY(0);
  }
}
.jump {
  transform-origin: 50% 50%;
  /* animation: jump 1s cubic-bezier(0.645, 0.045, 0.355, 1) alternate infinite; */
  background: #d32531 !important;
	color: #fff;
}

.jump-success {
  transform-origin: 50% 50%;
  /* animation: jump 1s cubic-bezier(0.645, 0.045, 0.355, 1) alternate 3; */
  background: #ceead5 !important;
}
</style>

	<div class="l-page__content">

		<div class="l-section l-section--standard-template l-section--no-separator">
			<div class="c-content c-content--no-sidebar t-copy">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						?>

						<h1><?php the_title(); ?></h1>
						<?php the_content(); ?>

            <?php if ( isset( $success ) && $success ) : ?>
              <div class="alert alert-success alert-dismissible fade show collapse jump-success" role="alert" style="display: block;">
                <div class="alert-text">You have been successfully subscribed.</div>
              </div>
            <?php endif; ?>

<form action="" method="POST" id="form">
						<section id="subscriptions">

  <div class="row mb-4">
    <div class="col-lg-9">
      <h1>Your Email Alerts &amp; Newsletters</h1>
			<h2>You will be subscribed to the following emails highlighted in red. To unsubscribe click on an option below to remove it and then click the submit button.</h2>
			<div class="input-group">
					<input type="text" required="required" class="email form-control" placeholder="Email Address*" name="email">
					<button class="nav-item nav-link save jump" type="submit" id="btn-save" >Submit</button>
			</div>
    </div>
    <div class="col-lg-3" align="right" style="padding-bottom: 5px;">
      <input type="checkbox" onclick="toggle(this);" id="toggleCheckboxes" checked> Select/Unselect All
    </div>
  </div>


  <div class="row row-cards">

    <div class="col-lg-4 col-sm-12">
      <input class="c-card" type="checkbox" id="RSAus" name="list[]" value="435b42b91d" checked>
      <div class="card-content">
        <div class="card-state-icon"></div>
        <label for="RSAus">
          <div class="card-text">
           <h4>Rolling Stone Australia</h4>
					 <p><strong> </strong></p>
          <p>Receive the Rolling Stone newsletter for leading authority on music, politics, sports and culture.</p>
          </div>
          <div class="card-bottom">
            <span>Daily</span>
          </div>
        </label>
      </div>
    </div>

		<!-- <div class="col-lg-4 col-sm-12">
      <input class="c-card" type="checkbox" id="RSAusMusic" name="list[]" value="435b42b91d-3f9afcad9d" checked>
      <div class="card-content">
        <div class="card-state-icon"></div>
        <label for="RSAusMusic">
          <div class="card-text">
           <h4>Rolling Stone Australia: Music</h4>
					 <p><strong> </strong></p>
          <p>Receive the Rolling Stone music newsletter.</p>
          </div>
          <div class="card-bottom">
            <span>Daily</span>
          </div>
        </label>
      </div>
    </div>

    <div class="col-lg-4 col-sm-12">
      <input class="c-card" type="checkbox" id="RSAusSport" name="list[]" value="435b42b91d-07edbc45ef" checked>
      <div class="card-content">
        <div class="card-state-icon"></div>
        <label for="RSAusSport">
          <div class="card-text">
           <h4>Rolling Stone Australia: Sport</h4>
					 <p><strong> </strong></p>
          <p>Receive the Rolling Stone sport newsletter.</p>
          </div>
          <div class="card-bottom">
            <span>Daily</span>
          </div>
        </label>
      </div>
    </div>

    <div class="col-lg-4 col-sm-12">
      <input class="c-card" type="checkbox" id="RSAusPolitics" name="list[]" value="435b42b91d-6ed288d25f" checked>
      <div class="card-content">
        <div class="card-state-icon"></div>
        <label for="RSAusPolitics">
          <div class="card-text">
           <h4>Rolling Stone Australia: Politics</h4>
					 <p><strong> </strong></p>
          <p>Receive the Rolling Stone politics newsletter.</p>
          </div>
          <div class="card-bottom">
            <span>Daily</span>
          </div>
        </label>
      </div>
    </div> -->

    <div class="col-lg-4 col-sm-12">
      <input class="c-card" type="checkbox" id="ToneDeaf" name="list[]" value="b6f823df63" checked>
      <div class="card-content">
        <div class="card-state-icon"></div>
        <label for="ToneDeaf">
          <div class="card-text">
           <h4>Tone Deaf</h4>
					 <p><strong>Music News And Free Tickets</strong></p>
          <p>Tone Deaf is Australian's premiere online publication dedicated to music lovers. Find out the latest music news, new releases and upcoming tours and festivals.</p>
          </div>
          <div class="card-bottom">
            <span>Daily</span>
          </div>
        </label>
      </div>
    </div>

		<div class="col-lg-4 col-sm-12">
      <input class="c-card" type="checkbox" id="TheBrag" name="list[]" value="c9114493ef" checked>
      <div class="card-content">
        <div class="card-state-icon"></div>
        <label for="TheBrag">
          <div class="card-text">
           <h4>The Brag</h4>
					 <p><strong>Arts, Music, Comedy And Food</strong></p>
          <p>The BRAG covers music, arts, pop culture, theatre, comedy, food, current affairs, and more - focusing nationally and beyond.</p>
          </div>
          <div class="card-bottom">
            <span>Daily</span>
          </div>
        </label>
      </div>
    </div>

		<div class="col-lg-4 col-sm-12">
      <input class="c-card" type="checkbox" id="TIO" name="list[]" value="4a1cd6d6a6" checked>
      <div class="card-content">
        <div class="card-state-icon"></div>
        <label for="TIO">
          <div class="card-text">
           <h4>The Industry Observer</h4>
					 <p><strong>Music Business News &amp; Insight</strong></p>
          <p>At its core, The Industry Observer is illuminating, informative and at times, argumentative - but in a non-provocative way.</p>
          </div>
          <div class="card-bottom">
            <span>Daily</span>
          </div>
        </label>
      </div>
    </div>

		<div class="col-lg-4 col-sm-12">
      <input class="c-card" type="checkbox" id="DBU" name="list[]" value="2a48cd9086" checked>
      <div class="card-content">
        <div class="card-state-icon"></div>
        <label for="DBU">
          <div class="card-text">
           <h4>Don't Bore Us</h4>
					 <p><strong>All things pop, punk and emo</strong></p>
          <p>DBU has a penchant for all things pop and alternative music. While new to the scene, Don’t Bore Us is easily found, right in the middle of the dance floor.</p>
          </div>
          <div class="card-bottom">
            <span>Daily</span>
          </div>
        </label>
      </div>
    </div>

		<!-- <div class="col-lg-4 col-sm-12">
      <input class="c-card" type="checkbox" id="TBD" name="list[]" value="f0eedde184" checked>
      <div class="card-content">
        <div class="card-state-icon"></div>
        <label for="TBD">
          <div class="card-text">
           <h4>The Brag Dad</h4>
					 <p><strong>Making your dad life your best life</strong></p>
          <p>A dedicated publication just for Australian dads, coming soon. Making your dad life, your best life.</p>
          </div>
          <div class="card-bottom">
            <span>Daily</span>
          </div>
        </label>
      </div>
    </div> -->

		<div class="col-lg-4 col-sm-12">
      <input class="c-card" type="checkbox" id="ToneCountry" name="list[]" value="eae466a458" checked>
      <div class="card-content">
        <div class="card-state-icon"></div>
        <label for="ToneCountry">
          <div class="card-text">
           <h4>Tone Country</h4>
					 <p><strong> </strong></p>
          <p>Tone Country is Australian’s premiere online publication dedicated to country music, artists and fans. Find out the latest music news, new releases and upcoming tours and festivals.</p>
          </div>
          <div class="card-bottom">
            <span>Daily</span>
          </div>
        </label>
      </div>
    </div>

  </div>


</section>

</form>

<script type="text/javascript">
  function toggle(source) {
		console.log( source );
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
	}
</script>

						<?php
					endwhile;
				endif;
				?>
			</div><!-- /.c-content t-copy -->
		</div><!-- /.l-section -->

		<?php get_template_part( 'template-parts/footer/footer' ); ?>
	</div><!-- .l-page__content -->

<?php
get_footer();
