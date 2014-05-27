<?php

require("lib/TLogger.php");
$log = new TLogger('uploads/my-log-file.log');

define('SELF', dirname($_SERVER['REQUEST_URI']));

$ds = DIRECTORY_SEPARATOR;
$storeFolder = 'uploads';   //2

if (!empty($_FILES)) {
  $tempFile = $_FILES['file']['tmp_name'];
  $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
  $targetFile =  $_FILES['file']['name'];
  $targetFile = $targetPath.md5(rand().$targetFile.rand()).".xml";

  move_uploaded_file($tempFile,$targetFile);
  $log->debug("Outputting ".basename($targetFile));
  die(basename($targetFile));
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>Sprint Analysis</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">

      /* Sticky footer styles
      -------------------------------------------------- */

      html,
      body {
        height: 100%;
        /* The html and body elements cannot have any padding or margin. */
      }

      /* Wrapper for page content to push down footer */
      #wrap {
        min-height: 100%;
        height: auto !important;
        height: 100%;
        /* Negative indent footer by it's height */
        margin: 0 auto -60px;
      }

      /* Set the fixed height of the footer here */
      #push,
      #footer {
        height: 60px;
      }
      #footer {
        background-color: #f5f5f5;
      }

      /* Lastly, apply responsive CSS fixes as necessary */
      @media (max-width: 767px) {
        #footer {
          margin-left: -20px;
          margin-right: -20px;
          padding-left: 20px;
          padding-right: 20px;
        }
      }



      /* Custom page CSS
      -------------------------------------------------- */
      /* Not required for template or sticky footer method. */
      #wrap > .container {
        padding-top: 60px;
      }

      .container {
        width: auto;
        max-width: 900px;
      }
      .container .credit {
        margin: 20px 0;
      }

    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="ico/favicon.png">

    <link rel="stylesheet" type="text/css" href="css/styles.css"></style>
    <link href="css/dropzone.css" type="text/css" rel="stylesheet" />

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/humanize.js"></script>
    <script type="text/javascript" src="js/dropzone.js"></script>
    <script type="text/javascript" src="js/sprint-report.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script type="text/javascript">
      function dirname(path) {
        return path.replace(/\\/g, '/').replace(/\/[^\/]*\/?$/, '');
      }

      Dropzone.options.myAwesomeDropzone = {
        init: function() {
          this.on("success",
            function(file) {
              document.location.href="/toomasr/sprint-analysis/?report="+file.xhr.response;
            }
          );
        }
      };
    </script>


<?php
  if (isset($_GET['report']) && strlen($_GET['report'])>0) {
    $reportUrl = SELF."/uploads/".$_GET['report'];
?>
    <script type="text/javascript">
      function drawSprintReport() {
        visualizeData('<?php echo $reportUrl; ?>');
      }

      google.load('visualization', '1.0', {'packages':['corechart']});
      google.setOnLoadCallback(drawSprintReport);
    </script>
<?php
  }
?>
  </head>

<body>

<!-- Part 1: Wrap all page content here -->
    <div id="wrap">

      <!-- Fixed navbar -->
      <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="brand" href="<?php echo SELF; ?>">JIRA Sprint Insight</a>
            <div class="nav-collapse collapse">
              <ul class="nav">
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li class="nav-header">Nav header</li>
                    <li><a href="#">Separated link</a></li>
                    <li><a href="#">One more separated link</a></li>
                  </ul>
                </li>
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </div>
      </div>

      <!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <h1>JIRA Sprint Insight</h1>
        </div>
<?php
  if (isset($_GET['report'])) {
?>
        <table id="general">
                <tr>
                        <td>
                                <div id="issue_o_hours_chart"></div>
                        </td>
                        <td>
                                <div id="issue_hours_chart"></div>
                        </td>
                 </tr>
                <tr>
                        <td>
                                <div id="issue_count_chart"></div>
                        </td>
                 </tr>
        </table>

        <table id="issues">
        </table>
        <p></p>
<?php
  }
  else {
?>
        <p class="lead">
                We like to sprint and we like to retrospect with more metrics. Throw your JIRA XML export here and analyze yourself also.
        </p>
        <form action="<?php echo SELF;?>" class="dropzone" id="my-awesome-dropzone">
        </form>
<?php
  }
?>
      </div>

      <div id="push"></div>
    </div>

    <div id="footer">
      <div class="container">
        <p class="muted credit">From developer for developers <a href="http://twitter.com/toomasr">Toomas Römer</a>.</p>
      </div>
    </div>


  </body>
</html>
