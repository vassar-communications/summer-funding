<?php

/*
On return:
- Selecting a studies preference doesn't seem to change the search results,
and results are coming up with different preferences listed. Get this to the
point that the queries crafted by the form determine the search results.
- Document the query building function.
 */


// ------


// The following replaces all spaces in JSON keys
// with dashes. Having a space in a JSON key will
// cause errors, since queries take the form of
// element.xxx. Something like `element.Studies Preference` will break.

// Note: this changes the order of the keys, since the
// keys with the space replaced will be moved to the end
// of the array. That's not a problem in this situation; just
// pointing it out.

$data = file_get_contents(getcwd() . '/data.json');
$arr = json_decode($data, true);

foreach ($arr as $i => $values) {
    foreach ($values as $key => $value) {
      // Gets the $ out of the amount so this can be turned
      // into a number for comparison purposes
      if($key == 'Amount') {
        $values[$key] = str_replace('$', '', $values[$key]);
        $values[$key] = str_replace(',', '', $values[$key]);
        $values[$key] = intval($values[$key]);
      }
      if(
          ($key == 'Areas of Interest') ||
          ($key == 'Support Type') ||
          ($key == 'Eligibility') ||
          ($key == 'Major Restriction')
        ) {
        $values[$key] = str_replace(',', ', ', $values[$key]);
      }
      if(strpos($key, ' ')) {
        $new_key = str_replace(' ', '_', $key);
        $values[$new_key] = $values[$key];
        unset($values[$key]);
      }
    }
    $arr[$i] = $values;
}

$data_json = json_encode($arr);

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <!-- alerts -->

  <script>const alerts = {
      tryLoadAlert() {
        if( alerts.DOMLoaded === true && alerts.getAlertResponse === true){
          const insertElement = document.querySelector("body");
          insertElement.insertAdjacentHTML('beforebegin', alerts.html);
        }
      },
      loadData() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            try {
              alerts_json = JSON.parse(xhttp.responseText);
              if(alerts_json[0].title){
                var backhex = alerts_json[0].channel == 2 ? 'B00C12' : '0A50B2';
                var date = new Date(alerts_json[0].pubdate+'Z');
                var df = new Intl.DateTimeFormat('en-us', {
                  weekday: 'long',
                  month: 'long',
                  day: 'numeric',
                  timeZone: 'America/New_York'
                });
                var tf = new Intl.DateTimeFormat('en-us', {
                  hour: 'numeric',
                  minute: 'numeric',
                  timeZone: 'America/New_York'
                });
                var buttons = '';
                if ( alerts_json.length >= 2 ) {
                  buttons = '<div class="buttons"><a class="btn btn-outline-light" href="/alerts" style="color:#FFF">More Information</a></div>';
                }
                var itemHTML = `<article role="article" class="node--type-alert clearfix" style="background-color:#${backhex}; color:#FFF; overflow:hidden; padding:2rem">
                <div class="content" style="max-width:48rem; margin:0 auto;color:#FFF">
                <h2 style="color:#FFF">${alerts_json[0].title}</h2>
                <p><time datetime="${date.toISOString()}" class="datetime">${df.format(date)}<br />${tf.format(date)}</time></p>
                ${alerts_json[0].body}
                ${buttons}
                </div>
                </article>`.trim();
                alerts.getAlertResponse = true;
                alerts.html = itemHTML;
                alerts.tryLoadAlert ();
              }
            } catch (e) { }
          }
        };

  xhttp.open("GET", "https://www.vassar.edu/alerts/rest?_format=json&t="+ Math.floor(new Date()/1000), true);

  //      xhttp.open("GET", "/alerts/rest?_format=json&t="+ Math.floor(new Date()/1000), true);
        xhttp.send();
      },
      tryLoadAlert () {
        if( alerts.DOMLoaded === true && alerts.getAlertResponse === true){
          const insertElement = document.querySelector("body");
          insertElement.insertAdjacentHTML('afterbegin', alerts.html);
        }
      }
     }
     //
      alerts.loadData();
      document.addEventListener("DOMContentLoaded", function(event) {
        alerts.DOMLoaded = true;
        alerts.tryLoadAlert ();
      });</script>

  <!-- end alerts -->

  <!-- Global site tag (gtag.js) - Google Analytics -->

  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-301357-5"></script>
  <script>
   window.dataLayer = window.dataLayer || [];
   function gtag(){dataLayer.push(arguments);}
   gtag('js', new Date());
   gtag('config', 'UA-301357-5');
  </script>

  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-WCS4M7');</script>
  <!-- End Google Tag Manager -->


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Summer Funding</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<link rel="icon" href="https://www.vassar.edu/themes/custom/vassar_bootstrap/favicon.ico" type="image/vnd.microsoft.icon" />

</head>

<body class="unfiltered">

<header class="masthead">
  <div class="masthead-logo">
    <a href="https://www.vassar.edu/">
<svg aria-labelledby="vassar-title" role="img" width="350" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2002 396"><title id="vassar-title">Vassar</title><path d="M379 32a104 104 0 0 1 22-17l-1-6-56 1-69-1-1 6c7 3 16 8 22 16 6 9 13 21 13 43 0 31-14 74-44 136l-49 108h-1c-20-49-44-101-67-153l-30-69c-7-19-12-34-13-46 3-18 18-30 35-35l-1-6-71 1L1 9l-1 7c22 6 41 31 55 58a4241 4241 0 0 1 142 322h7l23-63 53-117c33-73 56-122 72-148 9-14 17-26 27-36zM493 0l-7 1-24 64-52 116c-31 69-56 122-74 152-8 14-16 24-25 33-9 8-15 13-22 16l1 6 51-1 74 1v-6c-7-3-16-7-22-15-8-9-14-20-14-41 0-20 8-49 20-78l73-1 78 1 22 54c7 18 13 34 13 47-2 17-18 28-34 33v6l67-1 38 1c-4-13-89-209-116-271L493 0zm-21 232l-67-1 19-44 50-111h1l67 154c-21 2-45 2-70 2zM819 183c-28-14-54-26-69-42a66 66 0 0 1-19-49c0-44 33-70 68-77 20 3 41 14 61 33a209 209 0 0 1 43 59l7-1c-3-31-5-69-9-89-22-7-56-14-95-14-34 0-64 9-87 28a95 95 0 0 0-34 77c0 31 13 53 29 69 18 20 45 32 75 48s59 31 73 49a54 54 0 0 1 12 34c0 39-33 66-74 73-20-4-43-12-69-33-19-17-38-39-49-63l-17 4c0 1 29 73 41 99 24 2 58 6 85 6 43 0 77-13 98-32 22-19 31-42 31-69 0-57-48-84-101-110zM2000 378a60 60 0 0 1-10 1c-19-5-53-27-78-53-33-36-68-84-89-122 41-16 79-51 79-102 0-32-13-54-37-71-28-18-61-22-106-22l-87 1-76-1-1 6c8 1 19 4 26 8a57 57 0 0 1 22 23c7 12 10 29 11 55l2 100v24l-2 69c-1 28-4 47-14 62l-1 1c-12 18-39 19-54 2-9-10-17-22-24-35A3434 3434 0 0 1 1419 0l-7 1-24 64-52 116c-31 69-55 122-73 152-9 14-16 24-26 33-8 8-15 13-21 16v6l51-1 74 1 1-6c-8-3-16-7-23-15-7-9-14-20-14-41 0-20 9-49 21-78l72-1 78 1 23 54c6 18 12 34 13 47-3 17-19 28-35 33l1 6 67-1h132l89 1v-7c-12-2-25-6-37-15-14-12-23-26-25-57-2-26-3-57-3-86v-15l71 2c27 50 55 96 88 134 14 16 27 26 42 35s33 12 53 12a176 176 0 0 0 47-7zm-602-146l-66-1 18-44 50-111h2l66 154c-21 2-45 2-70 2zm392-35c-32 0-67-3-89-8v-11c0-52 1-110 6-154a265 265 0 0 1 40-3c31 2 55 9 76 28 17 16 27 38 27 65 0 33-10 62-36 82l-24 1zM1113 183c-28-14-54-26-69-42a66 66 0 0 1-19-49c0-44 33-70 68-77 20 3 41 14 61 33a209 209 0 0 1 43 59l7-1c-3-31-5-69-9-89-22-7-56-14-95-14-34 0-64 9-87 28a95 95 0 0 0-34 77c0 31 13 53 29 69 18 20 45 32 75 48s59 31 73 49a54 54 0 0 1 12 34c0 39-33 66-74 73-20-4-43-12-69-33-19-17-38-39-48-63l-18 4c0 1 29 73 41 99 24 2 58 6 85 6 43 0 77-13 98-32 22-19 31-42 31-69 0-57-48-84-101-110z"></path><image alt="Vassar" src="/assets/images/vassar-logo.png" width="402" height="80"></image></svg></a>
</div>

<div class="masthead-banner-area">
  <img class="masthead-banner" src="assets/images/0085-21-08-kr-summer-vassar-0003-banner.jpg" alt="" />
</div>

<h1>Summer Funding</h1>

</header>

<main class="main-content">

  <div class="intro-content">
<p class="intro-text">Vassar offers a wide range of support for students to fund their summer experiences, including internships, research, projects, language study, and more.</p>

<p>This tool allows students to locate programs, fellowships, and grants across a range of sponsoring departments to connect with relevant opportunities. For the most up-to-date information, please visit the sponsoring department’s website.</p>
</div>

<!--
    <div id="item-template" style="display: none">
        <div class="item">
            <h3><a href="{{Application_Link}}">{{Name}}</a></h3>
            <p>{{Status}}</p>
            <p>{{Studies_Preference}}</p>
            <p>{{Eligibility}}</p>
            <p class="note">{{Note}}</p>
        </div>
    </div>
-->

    <style>
        .layout {}
    </style>

    <div class="layout">
        <!-- FORM AREA -->

        <div class="filter_controls">
            <div class="filter_controls__inner">
                <h2 class="filter_controls__title">Filter by:</h2>

                <form id="filterForm">


                  <details>
                      <summary>Sponsor</summary>


                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-cce" name="Primary_Sponsoring_Department"
                              value="Center for Career Education">
                          <label for="sponsor-cce">Center for Career Education</label>
                      </span>


<!--                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-grants" name="Primary_Sponsoring_Department"
                              value="Grants Office">
                          <label for="sponsor-grants">Grants Office</label>
                      </span>
                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-registrar" name="Primary_Sponsoring_Department"
                              value="Registrar">
                          <label for="sponsor-registrar">Registrar</label>
                      </span>
                      -->
                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-dos" name="Primary_Sponsoring_Department"
                              value="Dean of Studies Office">
                          <label for="sponsor-dos">Dean of Studies Office</label>
                      </span>
                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-fellowships" name="Primary_Sponsoring_Department"
                              value="Fellowships Office">
                          <label for="sponsor-fellowships">Fellowships Office</label>
                      </span>

                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-msp" name="Primary_Sponsoring_Department"
                              value="Media Studies Program">
                          <label for="sponsor-msp">Media Studies Program</label>
                      </span>

                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-cel" name="Primary_Sponsoring_Department"
                              value="Office of Community-Engaged Learning">
                          <label for="sponsor-cel">Office of Community-Engaged Learning</label>
                      </span>

                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-progdir" name="Primary_Sponsoring_Department"
                              value="Program Director">
                          <label for="sponsor-progdir">Program Director/Faculty-led</label>
                      </span>

                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-seo" name="Primary_Sponsoring_Department"
                              value="Student Employment Office">
                          <label for="sponsor-seo">Student Employment Office</label>
                      </span>
<!--
                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-hr" name="Primary_Sponsoring_Department"
                              value="Human Resources">
                          <label for="sponsor-hr">Human Resources</label>
                      </span>
                      -->

                      <!--
                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-esp" name="Primary_Sponsoring_Department"
                              value="Environmental Studies Program">
                          <label for="sponsor-esp">Environmental Studies Program</label>
                      </span>
                      -->


<?php /*
                    <fieldset>
                        <legend>General Categories</legend>

                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-acad" name="Primary_Sponsoring_Department"
                              value="Academic Department">
                          <label for="sponsor-acad">Academic Department</label>
                      </span>
                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-fac" name="Primary_Sponsoring_Department"
                              value="Faculty Director">
                          <label for="sponsor-fac">Faculty Director</label>
                      </span>
                      <span class="item--switch">
                          <input class="sponsor" type="checkbox" id="sponsor-progdir" name="Primary_Sponsoring_Department"
                              value="Program Director">
                          <label for="sponsor-progdir">Program Director</label>
                      </span>
<fieldset>
*/ ?>

                  </details>

                    <details>
                        <summary>Eligibility</summary>

                        <span class="item--switch">
                            <input class="student-subset eligibility" type="checkbox" id="firstyear" name="Eligibility"
                                value="First-year">
                            <label for="firstyear">First-year</label>
                        </span>
                        <span class="item--switch">
                            <input class="student-subset eligibility" type="checkbox" id="sophomore" name="Eligibility"
                                value="Sophomore">
                            <label for="sophomore">Sophomore</label>
                        </span>
                        <span class="item--switch">
                            <input class="student-subset eligibility" type="checkbox" id="junior" name="Eligibility"
                                value="Junior">
                            <label for="junior">Junior</label>
                        </span>
                        <span class="item--switch">
                            <input class="student-subset eligibility" type="checkbox" id="senior" name="Eligibility"
                                value="Senior">
                            <label for="senior">Senior</label>
                        </span>

<?php /*
                        <span class="item--switch">
                            <input type="checkbox" class="eligibility" id="alums" name="Eligibility"
                                value="Alums">
                            <label for="alums">Alums</label>
                        </span>
*/ ?>

                    </details>

                    <details>
                        <summary>Areas of Interest</summary>

                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-arts" name="Areas_of_Interest"
                                value="Arts">
                            <label for="apref-arts">Arts</label>
                        </span>
                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-business" name="Areas_of_Interest"
                                value="Business">
                            <label for="apref-business">Business</label>
                        </span>
                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-entrepreneurship" name="Areas_of_Interest"
                                value="Entrepreneurship">
                            <label for="apref-entrepreneurship">Entrepreneurship</label>
                        </span>
                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-environment" name="Areas_of_Interest"
                                value="Environment">
                            <label for="apref-environment">Environment</label>
                        </span>
                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-government" name="Areas_of_Interest"
                                value="Government">
                            <label for="apref-government">Government</label>
                        </span>
                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-humanities" name="Areas_of_Interest"
                                value="Humanities">
                            <label for="apref-humanities">Humanities</label>
                        </span>
                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-international" name="Areas_of_Interest"
                                value="International">
                            <label for="apref-international">International</label>
                        </span>
                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-language" name="Areas_of_Interest"
                                value="Language">
                            <label for="apref-language">Language</label>
                        </span>
                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-nonprofit" name="Areas_of_Interest"
                                value="Non-profit">
                            <label for="apref-nonprofit">Non-profit</label>
                        </span>
                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-research" name="Areas_of_Interest"
                                value="Research">
                            <label for="apref-research">Research</label>
                        </span>
                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-science" name="Areas_of_Interest"
                                value="Science">
                            <label for="apref-science">Science</label>
                        </span>
                        <span class="item--switch">
                            <input class="area-pref" type="checkbox" id="apref-socialscience" name="Areas_of_Interest"
                                value="Social Science">
                            <label for="apref-socialscience">Social Science</label>
                        </span>

                    </details>



                    <details>
                        <summary>Support Type</summary>

                        <span class="item--switch">
                            <input class="support-pref" type="checkbox" id="support-internship" name="Support_Type"
                                value="Internship">
                            <label for="support-internship">Internship</label>
                        </span>
                        <span class="item--switch">
                            <input class="support-pref" type="checkbox" id="support-project" name="Support_Type"
                                value="Project">
                            <label for="support-project">Project</label>
                        </span>
                        <span class="item--switch">
                            <input class="support-pref" type="checkbox" id="support-research" name="Support_Type"
                                value="Research">
                            <label for="support-research">Research</label>
                        </span>
                        <span class="item--switch">
                            <input class="support-pref" type="checkbox" id="support-travel" name="Support_Type"
                                value="Travel">
                            <label for="support-travel">Travel</label>
                        </span>
                        <span class="item--switch">
                            <input class="support-pref" type="checkbox" id="support-study" name="Support_Type"
                                value="Study">
                            <label for="support-study">Study</label>
                        </span>

                    </details>



<?php /*
                    <details>
                        <summary>Category</summary>

                        <input class="need-merit" type="radio" id="nm-merit" name="Need_or_Merit"
                            value="Merit-based"><label for="nm-merit">Merit-based</label>

                        <input class="need-merit" type="radio" id="nm-need" name="Need_or_Merit"
                            value="Need-sensitive"><label for="nm-need">Need-sensitive</label>

                        <input class="need-merit" type="radio" id="nm-na" name="Need_or_Merit"
                            value="Open"><label for="nm-na">Open</label>
                    </details>
*/ ?>

                </form>

                <button id="filterIt" class="button-large" onclick="filterProps.getResults()">Search</button>



                <div id="d"></div>
            </div>
        </div>
        <!-- END FORM AREA -->
        <div class="results-area">
          <div class="results-info">
            <div id="results-count"></div>
            <span class="default-results">Showing all results</span>
            <a href="" class="start-over-link">Start over</a>
          </div>
          <div id="results"></div>
      </div>
    </div>
</main>



<!-- FOOTER -->

<footer class="footer">

  <div class="footer-section text-light theme-dark-burgundy">
    <div class="footer__main-content-container">
      <div class="footer__main-content-primary text-center text-left-md">
        <a class="logo" href="https://vassar.edu"><svg class="vassar-logo-svg" aria-labelledby="vassar-title" role="img" width="100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2002 396">
            <title id="vassar-title">Vassar</title>
            <path d="M379 32a104 104 0 0 1 22-17l-1-6-56 1-69-1-1 6c7 3 16 8 22 16 6 9 13 21 13 43 0 31-14 74-44 136l-49 108h-1c-20-49-44-101-67-153l-30-69c-7-19-12-34-13-46 3-18 18-30 35-35l-1-6-71 1L1 9l-1 7c22 6 41 31 55 58a4241 4241 0 0 1 142 322h7l23-63 53-117c33-73 56-122 72-148 9-14 17-26 27-36zM493 0l-7 1-24 64-52 116c-31 69-56 122-74 152-8 14-16 24-25 33-9 8-15 13-22 16l1 6 51-1 74 1v-6c-7-3-16-7-22-15-8-9-14-20-14-41 0-20 8-49 20-78l73-1 78 1 22 54c7 18 13 34 13 47-2 17-18 28-34 33v6l67-1 38 1c-4-13-89-209-116-271L493 0zm-21 232l-67-1 19-44 50-111h1l67 154c-21 2-45 2-70 2zM819 183c-28-14-54-26-69-42a66 66 0 0 1-19-49c0-44 33-70 68-77 20 3 41 14 61 33a209 209 0 0 1 43 59l7-1c-3-31-5-69-9-89-22-7-56-14-95-14-34 0-64 9-87 28a95 95 0 0 0-34 77c0 31 13 53 29 69 18 20 45 32 75 48s59 31 73 49a54 54 0 0 1 12 34c0 39-33 66-74 73-20-4-43-12-69-33-19-17-38-39-49-63l-17 4c0 1 29 73 41 99 24 2 58 6 85 6 43 0 77-13 98-32 22-19 31-42 31-69 0-57-48-84-101-110zM2000 378a60 60 0 0 1-10 1c-19-5-53-27-78-53-33-36-68-84-89-122 41-16 79-51 79-102 0-32-13-54-37-71-28-18-61-22-106-22l-87 1-76-1-1 6c8 1 19 4 26 8a57 57 0 0 1 22 23c7 12 10 29 11 55l2 100v24l-2 69c-1 28-4 47-14 62l-1 1c-12 18-39 19-54 2-9-10-17-22-24-35A3434 3434 0 0 1 1419 0l-7 1-24 64-52 116c-31 69-55 122-73 152-9 14-16 24-26 33-8 8-15 13-21 16v6l51-1 74 1 1-6c-8-3-16-7-23-15-7-9-14-20-14-41 0-20 9-49 21-78l72-1 78 1 23 54c6 18 12 34 13 47-3 17-19 28-35 33l1 6 67-1h132l89 1v-7c-12-2-25-6-37-15-14-12-23-26-25-57-2-26-3-57-3-86v-15l71 2c27 50 55 96 88 134 14 16 27 26 42 35s33 12 53 12a176 176 0 0 0 47-7zm-602-146l-66-1 18-44 50-111h2l66 154c-21 2-45 2-70 2zm392-35c-32 0-67-3-89-8v-11c0-52 1-110 6-154a265 265 0 0 1 40-3c31 2 55 9 76 28 17 16 27 38 27 65 0 33-10 62-36 82l-24 1zM1113 183c-28-14-54-26-69-42a66 66 0 0 1-19-49c0-44 33-70 68-77 20 3 41 14 61 33a209 209 0 0 1 43 59l7-1c-3-31-5-69-9-89-22-7-56-14-95-14-34 0-64 9-87 28a95 95 0 0 0-34 77c0 31 13 53 29 69 18 20 45 32 75 48s59 31 73 49a54 54 0 0 1 12 34c0 39-33 66-74 73-20-4-43-12-69-33-19-17-38-39-48-63l-18 4c0 1 29 73 41 99 24 2 58 6 85 6 43 0 77-13 98-32 22-19 31-42 31-69 0-57-48-84-101-110z"></path>
            <image alt="Vassar College" src="https://www.vassar.edu/assets/images/vassar-logo.png" width="402" height="80"></image>
          </svg></a>

        <div class="footer-row">
          <a class="social-icon-link" href="https://www.google.com/maps/place/Vassar+College/@41.6869086,-73.8936575,16.16z/data=!4m5!3m4!1s0x0:0xe3c87d640882d54c!8m2!3d41.6866781!4d-73.8936812">

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Free 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. --><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 256c-35.3 0-64-28.7-64-64s28.7-64 64-64s64 28.7 64 64s-28.7 64-64 64z"></path></svg>
            124 Raymond Avenue, Poughkeepsie, New York 12604</a>
        </div>

        <!--
        <div class="pt-2 link-collection footer-row">
          <a href="/alumni/community/" class="me-3 my-2">Community <i class="px-1 fa-solid fa-circle-arrow-right"></i></a> <a href="/alumni/calendar/" class="me-3 my-2">Calendar <i class="px-1 fa-solid fa-circle-arrow-right"></i></a> <a href="/alumni/contact/" class="me-3 my-2">Contact <i class="px-1 fa-solid fa-circle-arrow-right"></i></a>
        </div>
-->
        <hr>

        <div class="pt-2 link_collection">
          <ul class="inline-list">

            <li><a href="https://www.vassar.edu/about" class="me-3 my-2">About</a></li>

            <li><a href="https://www.vassar.edu/academics" class="me-3 my-2">Academics</a></li>

            <li><a href="https://www.vassar.edu/admission" class="me-3 my-2">Admission</a></li>

            <li> <a href="https://www.vassar.edu/campuslife" class="me-3 my-2">Campus Life</a></li>

            <li> <a href="https://offices.vassar.edu/campus-safety/statistics/" class="me-3 my-2">Annual Security and Fire Safety Report</a></li>

            <li> <a href="https://offices.vassar.edu/eoaa/" class="me-3 my-2">Title IX</a></li>

          </ul>

          <ul class="inline-list mt-0">
            <li> <a class="social-icon-link" href="https://www.facebook.com/vassarcollege/">

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Free 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. --><path d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z"></path></svg>
              </a></li>
            <li> <a class="social-icon-link" href="https://twitter.com/vassar/">

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Free 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. --><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path></svg>
              </a></li>
            <li> <a class="social-icon-link" href="https://www.instagram.com/vassarcollege/">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Free 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. --><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg></a></li>
            <li><a class="social-icon-link" href="https://www.linkedin.com/edu/school?id=19077">

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Free 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. --><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"></path></svg>
              </a></li>
            <li><a class="social-icon-link" href="https://www.youtube.com/vassar/">

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Free 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. --><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"></path></svg>
              </a></li>
            <li> <a class="social-icon-link" href="https://www.flickr.com/photos/vassarcollegemedia/">

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Free 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. --><path d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM144.5 319c-35.1 0-63.5-28.4-63.5-63.5s28.4-63.5 63.5-63.5 63.5 28.4 63.5 63.5-28.4 63.5-63.5 63.5zm159 0c-35.1 0-63.5-28.4-63.5-63.5s28.4-63.5 63.5-63.5 63.5 28.4 63.5 63.5-28.4 63.5-63.5 63.5z"></path></svg>
              </a></li>
            <li><a class="social-icon-link" href="https://www.tiktok.com/@vassar_college">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Free 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. --><path d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"></path></svg></a></li>
            <li><a href="https://www.vassar.edu/social-media">Social&nbsp;Media&nbsp;Directory</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-feature-module">
        <a href="https://campaign.vassar.edu/" class="donate-btn footer-feature-module__link">
          <span class="feature-image-container">
          <img src="https://www.vassar.edu/sites/default/files/2022-10/2022-campaign-logo.gif" alt="A Vassar College logo is encircled by the text 'Fearlessly Consequential: A Campaign for Our Collective Future'"></span>
          <span class="module-link__label btn-link arrow-no-circle">Learn more about our comprehensive campaign</span></a>
      </div>

    </div>

  </div>

  <div class="footer-section text-light theme-darkest-burgundy text-center">

    <p>© 2023 Vassar College</p>

  </div>

  <a class="link-to-top" href="#top">Back to top</a>

</footer>

<!-- END FOOTER -->

    <script>

        var filterProps = {
            "data": <?php echo $data_json; ?>,
            templateHTML: '<div id="item-template" class="{{item_classes}}"><div class="item"><h3 class="item-title">{{website_link_start}}{{Name}}{{website_link_end}}</h3><p>{{Description}}</p><p class="{{Contact_Swap_Text}}">Contact: <a class="contact__name" href="mailto:{{Contact}}">{{Contact_Name}}</a>, {{Primary_Sponsoring_Department}}<span class="contact__none">No contact provided</span></p><p><strong>Deadline: {{Rolling}}</strong></p><div class="details-grid"><div class="grid-item"><b class="item-label">Support type:</b>{{Support_Type}}</div><div class="grid-item type-major-restriction"><b class="item-label">Major restriction:</b>{{Major_Restriction}}</div><div class="grid-item"><b class="item-label">Areas of Interest:</b>{{Areas_of_Interest}}</div><!--<div class="grid-item"><b class="item-label">Need/Merit:</b>{{Need_or_Merit}}</div>--><div class="grid-item"><b class="item-label">Typical Number of Awards:</b>{{Typical_Number_of_Awards}}</div><div class="grid-item"><b class="item-label">Eligibility:</b>{{Eligibility}}</div><div class="grid-item"><b class="item-label">Award Amount:</b>{{Award_Amount}}</div><!--<div class="grid-item"><b class="item-label">Summer Earnings Relief:</b>{{Summer_Earnings_Relief}}</div>--></div><p class="note">{{Note}}</p><p><a class="button app-link-btn" href="{{Application_Link}}">Apply</a><b class="error-text" style="display: none">No application link provided</b></p></div></div>',
            formStateCollated: {},
            getResults() {
                this.refreshFormState();
                const filteredArray = this.data.filter(this.customFilter);
                let plural = "s";
                let resultsCount = filteredArray.length;
                if (resultsCount < 2)
                  plural = "";
                if (resultsCount == 0)
                  plural = "s";


                $('#results-count').html(resultsCount + ' result' + plural);
                document.getElementById("results").innerHTML = this.formatMarkup(filteredArray);
            },
            refreshFormState() {
                this.formStateCollated = {};
                var formState = $('#filterForm').serializeArray();
                for (key in formState) {
                    //console.log("Test - key:"+key+" formState[key].name:"+formState[key].name+" formState[key].value:"+formState[key].value);
                    if (!Array.isArray(this.formStateCollated[formState[key].name])) {
                        this.formStateCollated[formState[key].name] = [];
                    }
                    this.formStateCollated[formState[key].name].push(formState[key].value);
                }
            },
            customFilter(item) {
                //If formStateCollated is empty, return false (no results)
                if (filterProps.formStateCollated.length == 0) return false;
                for (key in filterProps.formStateCollated) {
                    var found = false;
                    //Only if at least one in each fieldset is true do we want a return value
                    for (key2 in filterProps.formStateCollated[key]) {
                        if (item[key].includes(filterProps.formStateCollated[key][key2])) {
                            found = true;
                        }
                    }
                    if (found == false) return false
                }
                return true;
            },
            formatMarkup(returnedData) {
              var finalItemsHtml = '';
              for (var key in returnedData) {
                var value = returnedData[key];
                var listHtml = this.templateHTML;
                for (var key2 in value) {
                  var theValue = value[key2];

                  //  Specific overrides go here

                  if(key2 == 'Summer_Earnings_Relief') {
                    if (theValue == '') theValue = 'No';
                    else theValue = 'Yes';
                  }


                  if(key2 == 'Website') {
                    if (theValue == '') {
                      // no site link was provided
                      listHtml = listHtml.replace('{{website_link_start}}', '');
                      listHtml = listHtml.replace('{{website_link_end}}', '');
                    }
                    else {
                      listHtml = listHtml.replace('{{website_link_start}}', '<a href="' + theValue + '">');
                      listHtml = listHtml.replace('{{website_link_end}}', '</a>');
                    }
                  }



                  if(key2 == 'Application_Link') {
                    if (theValue == '') {
                      // no application link was provided
                      listHtml = listHtml.replace('{{item_classes}}', 'no-app-link');
                    }
                    else {
                      listHtml = listHtml.replace('{{item_classes}}', 'has-app-link');
                    }
                  }


                  if(key2 == 'Contact_Name') {
                     if (theValue == '') {
                      theValue = 'No contact provided';
                      listHtml = listHtml.replace('{{Contact_Swap_Text}}', 'noContact');
                    }
                    else {
                      listHtml = listHtml.replace('{{Contact_Swap_Text}}', 'yesContact');
                    }
                    var re = new RegExp('{{' + key2 + '}}', 'g');
                    listHtml = listHtml.replace(re, theValue);

                  }
                  else {
                    // the usual replacement
                    var re = new RegExp('{{' + key2 + '}}', 'g');
                    listHtml = listHtml.replace(re, theValue);
                  }
                }
                finalItemsHtml += listHtml;
              }
              return finalItemsHtml;
            }
        };
        filterProps.getResults();
        //CHRIS STUFF

        /* START FORM JS */


        $("#filterIt").click(function () {
          $('body').removeClass('unfiltered');
        });


        $(".student-subset").click(function () {
            $('#allstudents').prop('checked', false);
        });
        $("#allstudents").click(function () {
            $('.student-subset').prop('checked', false);
        });
    </script>

</body>

</html>
