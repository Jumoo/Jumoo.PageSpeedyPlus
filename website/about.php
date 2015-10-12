<?php include 'header.php'; ?>

<div class="row">
  <div class="col-xs-12 col-sm-offset-1 col-sm-8">
    <h2 class="page-header">What is PageSpeedy?</h2>
    <p>
      Pagespeedy started out as an experiment, looking at different ways we could
      analyse websites, and use existing services to measure and improve the
      performance of those sites.
    </p>

    <h3 class="page-header">Performance</h3>
    <p>
      Using the <a href="https://developers.google.com/speed/pagespeed/">Google Page Speed</a> API, an open source Accessibility checker, and
      a modified version of the wappalyzer feature detection scripts, Pagespeedy
      checks sites and returns information about their performance and what type
      of things they are running.
    </p>

    <h3 class="page-header">Page Speed Insight:</h3>
	<img src="img/pagespeed.png" alt="pagespeed" style="float: right;margin:0.4em;border: 1px solid #eee;">
    <p>
      Google's <a href="https://developers.google.com/speed/pagespeed/insights/">Page Speed Insights</a> offer a measure of page speed. A score between
      0 and 100 is given to every site for both Desktop and Mobile modes.
    </p>
    <p>
      A Page Speed Insight looks at much more than just file size, calculating
      the number of requests, response times and blocking scripts or styles that
      will slow down the time it takes before the user sees and can use a page.
    </p>

    <h3 class="page-header">Accessbility:</h3>
	<img src="img/achecker.png" alt="achecker" style="float: left;margin:0.4em;border: 1px solid #eee;">
    <p>
      <a href="http://achecker.ca/checker/index.php">AChecker</a> is an open source Accessibility checker, that checks websites
      against the WCAG2 Accessibility checklist. Accessibility is more than
      checklists, but this quick check gives a quick insight into how well a
      site is doing, and maybe areas to focus on.
    </p>

    <h3 class="page-header">Site Features and Apps:</h3>
    <p>
      Pagespeedy uses a slightly modified version of the <a href="https://wappalyzer.com/">Wappalyzer</a> feature
      detection scripts, this allows us to peek under the hood of a website and
      get an insight into how it’s built and managed.
    </p>

    <h2 class="page-header">New Sites</h2>
	<img src="img/newsites.png" alt="newsites" style="float: right;margin:0.4em;border: 1px solid #eee;">
    <p>
      PageSpeedy can be really useful for seeing if a website is performing to a
      level similar to those around it, but it also is great for spotting what’s
      happening in the world of local gov websites.
    </p>
    <p>
      As well as running through all the checks, PageSpeedy runs every month and
      takes screenshots and captures the html of every site. These snapshots
      allow us to compare websites month on month, and detect what has changed.
      With this information we produce the monthly newsites list, that shows you
      what sites have been launched each month.
    </p>
    <div class="text-center">
      <a href="newsites.php" class="btn btn-success btn-lg">Latest new sites</a>
    </div>
    <h2 class="page-header"><span class="logo text-danger">SiteSpeedy</span> Reports</h2>
    <p>
      PageSpeedy offers a quick insight into each council’s website by looking
      at the homepage. If you are looking for a more indepth analysis of a site,
      we offer SiteSpeedy Reports that look at your whole website.
    </p>
    <div class="text-center">
      <a href="reports.php" class="btn btn-primary btn-lg">Order a SiteSpeedy Report</a>
    </div>

  </div>
</div>

<?php include 'footer.php'; ?>
